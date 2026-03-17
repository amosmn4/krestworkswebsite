<?php
$admin_title='Support Tickets'; $admin_active='support';
require_once __DIR__ . '/admin-header.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    header('Content-Type: application/json'); csrfAbortIfInvalid();
    if ($_POST['action']==='update_ticket') {
        $pdo->prepare('UPDATE support_tickets SET status=?,priority=? WHERE id=?')->execute([$_POST['status'],$_POST['priority'],(int)$_POST['id']]);
        echo json_encode(['success'=>true,'message'=>'Ticket updated.']);
    } elseif ($_POST['action']==='reply') {
        $body=trim(htmlspecialchars($_POST['body']??'',ENT_QUOTES,'UTF-8'));
        if (mb_strlen($body)<3){echo json_encode(['success'=>false,'message'=>'Reply too short.']);exit;}
        $pdo->prepare('INSERT INTO ticket_replies (ticket_id,user_id,body,is_staff) VALUES(?,?,?,1)')->execute([(int)$_POST['ticket_id'],$_SESSION['user_id'],$body]);
        $pdo->prepare('UPDATE support_tickets SET status="in_progress" WHERE id=?')->execute([(int)$_POST['ticket_id']]);
        echo json_encode(['success'=>true,'message'=>'Reply sent.']);
    }
    exit;
}

$status  = $_GET['status']??'';
$page    = max(1,(int)($_GET['page']??1)); $perPg=15; $offset=($page-1)*$perPg;
$where   = ['1=1']; $params=[];
if ($status && in_array($status,['open','in_progress','resolved','closed'])) { $where[]='t.status=?'; $params[]=$status; }
$wsql=implode(' AND ',$where);
$total=(int)$pdo->prepare("SELECT COUNT(*) FROM support_tickets t WHERE $wsql")->execute($params)??(function() use($pdo,$wsql,$params){ $s=$pdo->prepare("SELECT COUNT(*) FROM support_tickets t WHERE $wsql");$s->execute($params);return(int)$s->fetchColumn();})();
$st=$pdo->prepare("SELECT t.*,u.name as user_name,u.email as user_email FROM support_tickets t LEFT JOIN users u ON u.id=t.user_id WHERE $wsql ORDER BY FIELD(t.status,'open','in_progress','awaiting_response','resolved','closed'),t.created_at DESC LIMIT ? OFFSET ?");
$st->execute(array_merge($params,[$perPg,$offset]));
$tickets=$st->fetchAll(PDO::FETCH_ASSOC);
$totalPages=max(1,ceil($total/$perPg));

$selId=(int)($_GET['ticket']??0); $selTicket=null; $selReplies=[];
if($selId){ $s2=$pdo->prepare('SELECT t.*,u.name as user_name,u.email as user_email FROM support_tickets t LEFT JOIN users u ON u.id=t.user_id WHERE t.id=?'); $s2->execute([$selId]); $selTicket=$s2->fetch(PDO::FETCH_ASSOC);
  if($selTicket){ $r2=$pdo->prepare('SELECT r.*,u.name as a_name,u.role as a_role FROM ticket_replies r JOIN users u ON u.id=r.user_id WHERE r.ticket_id=? ORDER BY r.created_at ASC'); $r2->execute([$selId]); $selReplies=$r2->fetchAll(PDO::FETCH_ASSOC); }
}
?>
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Support Tickets</h1></div>
  <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
    <?php foreach ([['','All'],['open','Open'],['in_progress','In Progress'],['resolved','Resolved']] as $f): ?>
    <a href="?status=<?= $f[0] ?>" style="padding:0.3rem 0.65rem;border-radius:999px;border:1px solid <?= $status===$f[0]?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= $status===$f[0]?'rgba(245,168,0,0.1)':'none' ?>;color:<?= $status===$f[0]?'var(--kw-primary)':'var(--kw-text-muted)' ?>;font-size:0.7rem;font-weight:700;text-decoration:none;"><?= $f[1] ?></a>
    <?php endforeach; ?>
  </div>
</div>
<div style="display:grid;grid-template-columns:1fr <?= $selTicket?'420px':'' ?>;gap:1.25rem;">
  <div class="adm-card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
    <table class="adm-table">
      <thead><tr><th>#</th><th>Subject</th><th>User</th><th>Priority</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
      <?php foreach($tickets as $t):
        $priColor=['critical'=>'#EF4444','high'=>'#F97316','medium'=>'#3B82F6','low'=>'#22C55E'][$t['priority']??'medium'];
        $sel=$selTicket&&$selTicket['id']==$t['id'];
      ?>
      <tr style="<?= $sel?'background:rgba(245,168,0,0.05);':'' ?>">
        <td style="font-size:0.68rem;color:var(--kw-text-muted);">#<?= $t['id'] ?></td>
        <td style="font-size:0.78rem;font-weight:600;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($t['subject']) ?></td>
        <td>
          <div style="font-size:0.72rem;font-weight:600;"><?= e($t['user_name']??'—') ?></div>
          <div style="font-size:0.62rem;color:var(--kw-text-muted);"><?= e($t['user_email']??'') ?></div>
        </td>
        <td><span style="background:<?=$priColor?>15;color:<?=$priColor?>;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.62rem;font-weight:700;"><?= ucfirst($t['priority']??'medium') ?></span></td>
        <td><select onchange="updateTicket(<?=$t['id']?>,this.value,'<?= $t['priority']??'medium' ?>')" style="padding:0.2rem 0.3rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-primary);cursor:pointer;">
          <?php foreach(['open','in_progress','resolved','closed'] as $st2): ?><option value="<?=$st2?>" <?=$t['status']===$st2?'selected':''?>><?= ucwords(str_replace('_',' ',$st2)) ?></option><?php endforeach; ?>
        </select></td>
        <td style="font-size:0.65rem;white-space:nowrap;"><?= date('d M',strtotime($t['created_at'])) ?></td>
        <td><a href="?ticket=<?=$t['id']?>" style="font-size:0.7rem;color:var(--kw-primary);">View</a></td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($tickets)): ?>
      <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--kw-text-muted);font-size:0.78rem;">No tickets.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
    </div>
  </div>
  <?php if($selTicket): ?>
  <div class="adm-card" style="padding:0;overflow:hidden;align-self:flex-start;position:sticky;top:calc(var(--adm-topbar)+0.75rem);max-height:85vh;overflow-y:auto;">
    <div style="padding:0.85rem 1rem;border-bottom:1px solid var(--kw-border);display:flex;justify-content:space-between;align-items:center;">
      <div><div style="font-size:0.65rem;color:var(--kw-text-muted);">Ticket #<?= $selTicket['id'] ?></div><h4 style="font-size:0.82rem;margin:0.1rem 0 0;"><?= e($selTicket['subject']) ?></h4></div>
      <a href="?status=<?=$status?>" style="font-size:0.7rem;color:var(--kw-text-muted);">✕</a>
    </div>
    <div style="padding:1rem;display:flex;flex-direction:column;gap:0.65rem;">
      <div style="background:var(--kw-bg-alt);border-radius:8px;padding:0.75rem;font-size:0.78rem;line-height:1.65;"><?= nl2br(e($selTicket['body'])) ?></div>
      <?php foreach($selReplies as $rep):
        $isStaff=(bool)$rep['is_staff'];
      ?>
      <div style="display:flex;gap:0.5rem;<?= !$isStaff?'flex-direction:row-reverse;':'' ?>">
        <div style="width:24px;height:24px;border-radius:50%;background:<?=$isStaff?'#3B82F6':'var(--kw-primary)'?>;color:<?=$isStaff?'#fff':'#0A0F1A'?>;display:flex;align-items:center;justify-content:center;font-size:0.55rem;font-weight:800;flex-shrink:0;margin-top:2px;"><?=$isStaff?'KW':strtoupper(substr($rep['a_name'],0,1))?></div>
        <div style="max-width:85%;">
          <div style="font-size:0.62rem;color:var(--kw-text-muted);margin-bottom:0.2rem;<?=!$isStaff?'text-align:right;':''?>"><?=$isStaff?'Support':''.e($rep['a_name'])?> · <?=date('d M H:i',strtotime($rep['created_at']))?></div>
          <div style="background:<?=$isStaff?'rgba(59,130,246,0.08)':'rgba(245,168,0,0.08)'?>;border:1px solid <?=$isStaff?'rgba(59,130,246,0.2)':'rgba(245,168,0,0.2)'?>;border-radius:8px;padding:0.65rem;font-size:0.78rem;line-height:1.6;"><?= nl2br(e($rep['body'])) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
      <form id="admin-reply-form">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="reply">
        <input type="hidden" name="ticket_id" value="<?= $selTicket['id'] ?>">
        <textarea name="body" class="adm-input" rows="3" placeholder="Type reply…" style="margin-bottom:0.5rem;resize:vertical;"></textarea>
        <div id="reply-alert"></div>
        <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
          <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" id="reply-btn"><i class="fa-solid fa-paper-plane"></i> Send Reply</button>
          <?php if($selTicket['user_email']): ?><a href="mailto:<?= e($selTicket['user_email']) ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-envelope"></i> Email</a><?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>
</div>
<script>
async function updateTicket(id,status,priority){
  const fd=new FormData();fd.append('action','update_ticket');fd.append('id',id);fd.append('status',status);fd.append('priority',priority);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r=await fetch(location.pathname,{method:'POST',body:fd}); const d=await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error');
}
document.getElementById('admin-reply-form')?.addEventListener('submit',async function(e){
  e.preventDefault(); const btn=document.getElementById('reply-btn'); btn.disabled=true;
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
  if(d.success){window.Krest?.toast(d.message,'success');setTimeout(()=>location.reload(),600);}
  else document.getElementById('reply-alert').innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:0.5rem;">${d.message}</div>`;
  btn.disabled=false;
});
</script>
<?php require_once __DIR__ . '/admin-footer.php'; ?>