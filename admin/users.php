<?php
$admin_title  = 'Users';
$admin_active = 'users';
require_once __DIR__ . '/admin-header.php';

$pdo = db();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json'); csrfAbortIfInvalid();
    $id = (int)($_POST['id'] ?? 0);
    if ($_POST['action'] === 'toggle_role') {
        $cur = $pdo->prepare('SELECT role FROM users WHERE id=?'); $cur->execute([$id]); $row=$cur->fetch();
        if ($row) { $newRole = $row['role']==='admin'?'client':'admin'; $pdo->prepare('UPDATE users SET role=? WHERE id=?')->execute([$newRole,$id]); echo json_encode(['success'=>true,'message'=>"Role changed to $newRole."]); } else echo json_encode(['success'=>false,'message'=>'User not found.']);
    } elseif ($_POST['action'] === 'deactivate') {
        $pdo->prepare('UPDATE users SET is_active=0 WHERE id=?')->execute([$id]); echo json_encode(['success'=>true,'message'=>'User deactivated.']);
    } elseif ($_POST['action'] === 'delete') {
        $pdo->prepare('DELETE FROM users WHERE id=? AND role != "admin"')->execute([$id]); echo json_encode(['success'=>true,'message'=>'User deleted.']);
    }
    exit;
}

$search = trim($_GET['q']??''); $role = $_GET['role']??''; $page = max(1,(int)($_GET['page']??1)); $perPg=20; $offset=($page-1)*$perPg;
$where=['1=1'];$params=[];
if ($search){$where[]='(name LIKE ? OR email LIKE ? OR company LIKE ?)';$params=["%$search%","%$search%","%$search%"];}
if ($role && in_array($role,['client','admin'])){$where[]='role=?';$params[]=$role;}
$wsql=implode(' AND ',$where);
$totalStmt=$pdo->prepare("SELECT COUNT(*) FROM users WHERE $wsql"); $totalStmt->execute($params); $total=(int)$totalStmt->fetchColumn();
$st=$pdo->prepare("SELECT * FROM users WHERE $wsql ORDER BY created_at DESC LIMIT ? OFFSET ?");
$st->execute(array_merge($params,[$perPg,$offset]));
$users=$st->fetchAll(PDO::FETCH_ASSOC);
$totalPages=max(1,ceil($total/$perPg));
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Users</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);"><?= number_format($total) ?> users</p></div>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
    <form method="GET" style="display:flex;gap:0;">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search users…" style="padding:0.45rem 0.75rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-right:none;border-radius:var(--kw-radius-md) 0 0 var(--kw-radius-md);font-size:0.78rem;color:var(--kw-text-primary);outline:none;width:180px;">
      <button type="submit" style="padding:0.45rem 0.75rem;background:var(--kw-primary);border:none;border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;cursor:pointer;color:#0A0F1A;"><i class="fa-solid fa-search"></i></button>
    </form>
    <a href="?role=client" style="padding:0.3rem 0.65rem;border-radius:999px;border:1px solid <?= $role==='client'?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= $role==='client'?'rgba(245,168,0,0.1)':'none' ?>;color:<?= $role==='client'?'var(--kw-primary)':'var(--kw-text-muted)' ?>;font-size:0.7rem;font-weight:700;text-decoration:none;">Clients</a>
    <a href="?role=admin" style="padding:0.3rem 0.65rem;border-radius:999px;border:1px solid <?= $role==='admin'?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= $role==='admin'?'rgba(245,168,0,0.1)':'none' ?>;color:<?= $role==='admin'?'var(--kw-primary)':'var(--kw-text-muted)' ?>;font-size:0.7rem;font-weight:700;text-decoration:none;">Admins</a>
    <?php if ($role||$search): ?><a href="?" style="font-size:0.7rem;color:var(--kw-text-muted);">✕ Clear</a><?php endif; ?>
  </div>
</div>

<div class="adm-card" style="padding:0;overflow:hidden;">
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>#</th><th>User</th><th>Company</th><th>Role</th><th>Subscriptions</th><th>Joined</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $user):
      $subCnt = (int)$pdo->prepare('SELECT COUNT(*) FROM subscriptions WHERE user_id=? AND status="active"')->execute([$user['id']]) ? (function() use($pdo,$user){ $s=$pdo->prepare('SELECT COUNT(*) FROM subscriptions WHERE user_id=? AND status="active"'); $s->execute([$user['id']]); return (int)$s->fetchColumn(); })() : 0;
    ?>
    <tr>
      <td style="font-size:0.7rem;color:var(--kw-text-muted);"><?= $user['id'] ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <div style="width:28px;height:28px;border-radius:50%;background:var(--kw-primary);color:#0A0F1A;display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:800;flex-shrink:0;">
            <?= strtoupper(substr($user['name'],0,2)) ?>
          </div>
          <div>
            <div style="font-weight:700;font-size:0.78rem;"><?= e($user['name']) ?></div>
            <div style="font-size:0.65rem;color:var(--kw-text-muted);"><?= e($user['email']) ?></div>
          </div>
        </div>
      </td>
      <td style="font-size:0.75rem;"><?= e($user['company']??'—') ?></td>
      <td><span class="adm-badge-inline badge-<?= $user['role'] ?>"><?= ucfirst($user['role']) ?></span></td>
      <td>
        <span style="background:rgba(34,197,94,0.12);color:#22C55E;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.65rem;font-weight:700;">
          <?= $subCnt ?> active
        </span>
      </td>
      <td style="font-size:0.68rem;white-space:nowrap;"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
      <td>
        <div style="display:flex;gap:0.3rem;">
          <button onclick="toggleRole(<?= $user['id'] ?>)" title="Toggle role" style="padding:0.22rem 0.5rem;background:rgba(245,168,0,0.1);border:1px solid rgba(245,168,0,0.2);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);cursor:pointer;"><i class="fa-solid fa-user-tag"></i></button>
          <a href="mailto:<?= e($user['email']) ?>" style="padding:0.22rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-muted);text-decoration:none;" title="Email"><i class="fa-solid fa-envelope"></i></a>
          <?php if ($user['role'] !== 'admin'): ?>
          <button onclick="admDelete('admin/users',<?= $user['id'] ?>,()=>location.reload())" style="padding:0.22rem 0.5rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:4px;font-size:0.65rem;color:#EF4444;cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
          <?php endif; ?>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($users)): ?>
    <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--kw-text-muted);font-size:0.82rem;">No users found.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
  </div>
  <?php if ($totalPages > 1): ?>
  <div style="padding:0.75rem 1rem;border-top:1px solid var(--kw-border);display:flex;gap:0.35rem;flex-wrap:wrap;">
    <?php for($p=1;$p<=$totalPages;$p++): ?><a href="?page=<?=$p?><?=$role?"&role=$role":''?><?=$search?"&q=".urlencode($search):''?>" style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;border-radius:4px;border:1px solid <?=$p===$page?'var(--kw-primary)':'var(--kw-border)'?>;background:<?=$p===$page?'var(--kw-primary)':'transparent'?>;color:<?=$p===$page?'#0A0F1A':'var(--kw-text-muted)'?>;font-size:0.68rem;font-weight:700;text-decoration:none;"><?=$p?></a><?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<script>
async function toggleRole(id) {
  const fd = new FormData(); fd.append('action','toggle_role'); fd.append('id',id);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r = await fetch(window.location.pathname,{method:'POST',body:fd});
  const d = await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error');
  if (d.success) setTimeout(()=>location.reload(),800);
}
</script>
<?php require_once __DIR__ . '/admin-footer.php'; ?>