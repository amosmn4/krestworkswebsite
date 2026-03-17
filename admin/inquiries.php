<?php
$admin_title  = 'Inquiries';
$admin_active = 'inquiries';
require_once __DIR__ . '/admin-header.php';

$pdo = db();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    csrfAbortIfInvalid();
    if ($_POST['action'] === 'update_status') {
        $id = (int)$_POST['id']; $status = $_POST['status'] ?? '';
        if (in_array($status, ['new','in_progress','resolved'])) {
            $pdo->prepare('UPDATE inquiries SET status=? WHERE id=?')->execute([$status, $id]);
            echo json_encode(['success'=>true,'message'=>'Status updated.']);
        } else echo json_encode(['success'=>false,'message'=>'Invalid status.']);
    } elseif ($_POST['action'] === 'delete') {
        $pdo->prepare('DELETE FROM inquiries WHERE id=?')->execute([(int)$_POST['id']]);
        echo json_encode(['success'=>true,'message'=>'Inquiry deleted.']);
    }
    exit;
}

$type   = $_GET['type']   ?? '';
$status = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');
$page   = max(1,(int)($_GET['page']??1));
$perPg  = 15;
$offset = ($page-1)*$perPg;

$where = ['1=1']; $params = [];
if ($type)   { $where[] = 'type=?';            $params[] = $type; }
if ($status) { $where[] = 'status=?';          $params[] = $status; }
if ($search) { $where[] = '(name LIKE ? OR email LIKE ? OR subject LIKE ?)'; $params = array_merge($params,["%$search%","%$search%","%$search%"]); }
$wsql = implode(' AND ',$where);

$total = (int)$pdo->prepare("SELECT COUNT(*) FROM inquiries WHERE $wsql")->execute($params) ? (function() use($pdo,$wsql,$params){ $s=$pdo->prepare("SELECT COUNT(*) FROM inquiries WHERE $wsql"); $s->execute($params); return (int)$s->fetchColumn(); })() : 0;
$s = $pdo->prepare("SELECT * FROM inquiries WHERE $wsql ORDER BY created_at DESC LIMIT ? OFFSET ?");
$s->execute(array_merge($params,[$perPg,$offset]));
$inquiries = $s->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1,ceil($total/$perPg));

// Selected inquiry
$selectedId  = (int)($_GET['id']??0);
$selectedInq = null;
if ($selectedId) { $s2=$pdo->prepare('SELECT * FROM inquiries WHERE id=?'); $s2->execute([$selectedId]); $selectedInq=$s2->fetch(PDO::FETCH_ASSOC); }
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Inquiries</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);"><?= number_format($total) ?> total</p></div>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
    <form method="GET" style="display:flex;gap:0;">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search…" style="padding:0.45rem 0.75rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-right:none;border-radius:var(--kw-radius-md) 0 0 var(--kw-radius-md);font-size:0.78rem;color:var(--kw-text-primary);outline:none;width:160px;">
      <button type="submit" style="padding:0.45rem 0.75rem;background:var(--kw-primary);border:none;border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;cursor:pointer;color:#0A0F1A;font-size:0.78rem;"><i class="fa-solid fa-search"></i></button>
    </form>
    <?php foreach ([['type','contact','Contact'],['type','demo','Demo'],['type','consultation','Consult'],['status','new','New']] as $f): ?>
    <a href="?<?= $f[0] ?>=<?= $f[1] ?>" style="padding:0.3rem 0.65rem;border-radius:999px;border:1px solid <?= ($_GET[$f[0]]??'')===$f[1]?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= ($_GET[$f[0]]??'')===$f[1]?'rgba(245,168,0,0.1)':'none' ?>;color:<?= ($_GET[$f[0]]??'')===$f[1]?'var(--kw-primary)':'var(--kw-text-muted)' ?>;font-size:0.7rem;font-weight:700;text-decoration:none;"><?= $f[2] ?></a>
    <?php endforeach; ?>
    <?php if ($type||$status||$search): ?><a href="?" style="font-size:0.7rem;color:var(--kw-text-muted);">✕ Clear</a><?php endif; ?>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr <?= $selectedInq ? '380px' : '' ?>;gap:1.25rem;">

  <div class="adm-card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
    <table class="adm-table">
      <thead><tr><th>#</th><th>From</th><th>Type</th><th>Subject</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($inquiries as $inq):
        $sc = $inq['status']==='new'?'badge-new':($inq['status']==='resolved'?'badge-resolved':'badge-open');
        $sel = $selectedInq && $selectedInq['id']==$inq['id'];
      ?>
      <tr style="<?= $sel?'background:rgba(245,168,0,0.05);':'' ?>">
        <td style="font-size:0.7rem;color:var(--kw-text-muted);">#<?= $inq['id'] ?></td>
        <td>
          <div style="font-weight:700;font-size:0.78rem;"><?= e($inq['name']??'—') ?></div>
          <div style="font-size:0.65rem;color:var(--kw-text-muted);"><?= e($inq['email']??'') ?></div>
          <?php if ($inq['company']): ?><div style="font-size:0.6rem;color:var(--kw-primary);"><?= e($inq['company']) ?></div><?php endif; ?>
        </td>
        <td><span class="adm-badge-inline badge-info"><?= ucfirst($inq['type']) ?></span></td>
        <td style="font-size:0.78rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($inq['subject']??'—') ?></td>
        <td>
          <select onchange="updateInqStatus(<?= $inq['id'] ?>,this.value)" style="padding:0.2rem 0.4rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.68rem;color:var(--kw-text-primary);cursor:pointer;">
            <?php foreach (['new','in_progress','resolved'] as $st): ?>
            <option value="<?= $st ?>" <?= $inq['status']===$st?'selected':'' ?>><?= ucwords(str_replace('_',' ',$st)) ?></option>
            <?php endforeach; ?>
          </select>
        </td>
        <td style="font-size:0.68rem;white-space:nowrap;"><?= date('d M Y', strtotime($inq['created_at'])) ?></td>
        <td>
          <div style="display:flex;gap:0.35rem;">
            <a href="?id=<?= $inq['id'] ?>" style="padding:0.25rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);text-decoration:none;white-space:nowrap;">View</a>
            <?php if ($inq['email']): ?><a href="mailto:<?= e($inq['email']) ?>" style="padding:0.25rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-muted);text-decoration:none;" title="Reply"><i class="fa-solid fa-reply"></i></a><?php endif; ?>
            <?php if ($inq['phone']): ?><a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$inq['phone']) ?>" target="_blank" style="padding:0.25rem 0.5rem;background:#25D36615;border:1px solid #25D36630;border-radius:4px;font-size:0.65rem;color:#25D366;text-decoration:none;" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a><?php endif; ?>
            <button onclick="admDelete('admin/inquiries',<?= $inq['id'] ?>,()=>location.reload())" style="padding:0.25rem 0.5rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:4px;font-size:0.65rem;color:#EF4444;cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($inquiries)): ?>
      <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--kw-text-muted);font-size:0.82rem;">No inquiries found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
    </div>
    <?php if ($totalPages > 1): ?>
    <div style="padding:0.85rem 1rem;border-top:1px solid var(--kw-border);display:flex;gap:0.4rem;flex-wrap:wrap;">
      <?php for($p=1;$p<=$totalPages;$p++): ?>
      <a href="?page=<?= $p ?><?= $type?"&type=$type":'' ?><?= $status?"&status=$status":'' ?><?= $search?"&q=".urlencode($search):'' ?>" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:4px;border:1px solid <?= $p===$page?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= $p===$page?'var(--kw-primary)':'transparent' ?>;color:<?= $p===$page?'#0A0F1A':'var(--kw-text-muted)' ?>;font-size:0.72rem;font-weight:700;text-decoration:none;"><?= $p ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </div>

  <?php if ($selectedInq): ?>
  <div class="adm-card" style="padding:0;overflow:hidden;align-self:flex-start;position:sticky;top:calc(var(--adm-topbar)+0.75rem);">
    <div style="padding:0.85rem 1rem;border-bottom:1px solid var(--kw-border);display:flex;justify-content:space-between;align-items:center;">
      <span style="font-size:0.78rem;font-weight:700;">Inquiry #<?= $selectedInq['id'] ?></span>
      <a href="?" style="font-size:0.7rem;color:var(--kw-text-muted);">✕ Close</a>
    </div>
    <div style="padding:1rem;max-height:80vh;overflow-y:auto;">
      <?php foreach ([
        ['Type',ucfirst($selectedInq['type'])],['Name',$selectedInq['name']??'—'],['Email',$selectedInq['email']??'—'],
        ['Phone',$selectedInq['phone']??'—'],['Company',$selectedInq['company']??'—'],['Subject',$selectedInq['subject']??'—'],
        ['Product',$selectedInq['product_interest']??'—'],['Date',date('d M Y H:i',strtotime($selectedInq['created_at']))],
      ] as $row): ?>
      <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);font-size:0.72rem;">
        <span style="color:var(--kw-text-muted);font-weight:700;"><?= $row[0] ?></span>
        <span style="font-weight:600;text-align:right;max-width:65%;word-break:break-word;"><?= e($row[1]) ?></span>
      </div>
      <?php endforeach; ?>
      <?php if ($selectedInq['message']): ?>
      <div style="margin-top:0.75rem;">
        <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;color:var(--kw-text-muted);margin-bottom:0.35rem;">Message</div>
        <div style="background:var(--kw-bg-alt);border-radius:6px;padding:0.75rem;font-size:0.78rem;line-height:1.65;"><?= nl2br(e($selectedInq['message'])) ?></div>
      </div>
      <?php endif; ?>
      <div style="margin-top:1rem;display:flex;flex-direction:column;gap:0.4rem;">
        <?php if ($selectedInq['email']): ?><a href="mailto:<?= e($selectedInq['email']) ?>" class="kw-btn kw-btn-primary kw-btn-sm" style="justify-content:center;"><i class="fa-solid fa-reply"></i> Reply via Email</a><?php endif; ?>
        <?php if ($selectedInq['phone']): ?><a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$selectedInq['phone']) ?>" target="_blank" class="kw-btn kw-btn-sm" style="justify-content:center;background:#25D366;color:#fff;border:none;"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a><?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
async function updateInqStatus(id, status) {
  const fd = new FormData(); fd.append('action','update_status'); fd.append('id',id); fd.append('status',status);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r = await fetch(window.location.pathname,{method:'POST',body:fd});
  const d = await r.json();
  window.Krest?.toast(d.message||(d.success?'Updated':'Failed'), d.success?'success':'error');
}
</script>
<?php require_once __DIR__ . '/admin-footer.php'; ?>