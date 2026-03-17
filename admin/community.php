<?php
$admin_title='Community'; $admin_active='community';
require_once __DIR__ . '/admin-header.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    header('Content-Type: application/json'); csrfAbortIfInvalid();
    if($_POST['action']==='toggle_post'){$pdo->prepare('UPDATE community_posts SET is_active=!is_active WHERE id=?')->execute([(int)$_POST['id']]);echo json_encode(['success'=>true,'message'=>'Post visibility toggled.']);}
    elseif($_POST['action']==='delete_post'){$pdo->prepare('DELETE FROM community_posts WHERE id=?')->execute([(int)$_POST['id']]);echo json_encode(['success'=>true,'message'=>'Post deleted.']);}
    elseif($_POST['action']==='delete_reply'){$pdo->prepare('DELETE FROM community_replies WHERE id=?')->execute([(int)$_POST['id']]);echo json_encode(['success'=>true,'message'=>'Reply deleted.']);}
    exit;
}

$posts=$pdo->query("SELECT cp.*,u.name as author_name,(SELECT COUNT(*) FROM community_replies WHERE post_id=cp.id) as reply_count,(SELECT COUNT(*) FROM community_likes WHERE post_id=cp.id) as like_count FROM community_posts cp LEFT JOIN users u ON u.id=cp.user_id ORDER BY cp.created_at DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);
$totalPosts=(int)$pdo->query("SELECT COUNT(*) FROM community_posts")->fetchColumn();
$activePosts=(int)$pdo->query("SELECT COUNT(*) FROM community_posts WHERE is_active=1")->fetchColumn();
$totalReplies=(int)$pdo->query("SELECT COUNT(*) FROM community_replies")->fetchColumn();
?>
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Community Moderation</h1></div>
  <a href="<?= url('community') ?>" target="_blank" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-eye"></i> View Community</a>
</div>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem;">
  <?php foreach([['fa-comments','#F5A800',$totalPosts,'Total Posts'],['fa-check-circle','#22C55E',$activePosts,'Active Posts'],['fa-reply','#3B82F6',$totalReplies,'Total Replies']] as $k): ?>
  <div class="adm-stat"><div class="adm-stat-icon" style="background:<?=$k[1]?>15;"><i class="fa-solid <?=$k[0]?>" style="color:<?=$k[1]?>;"></i></div><div><div class="adm-stat-value"><?=number_format($k[2])?></div><div class="adm-stat-label"><?=$k[3]?></div></div></div>
  <?php endforeach; ?>
</div>
<div class="adm-card" style="padding:0;overflow:hidden;">
  <div class="adm-card-head"><h4 class="adm-card-title">Recent Posts</h4></div>
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Replies</th><th>Likes</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($posts as $post): ?>
    <tr>
      <td><div style="font-weight:700;font-size:0.78rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($post['title']) ?></div></td>
      <td style="font-size:0.72rem;"><?= e($post['author_name']??'—') ?></td>
      <td><span style="background:var(--kw-bg-alt);border-radius:999px;padding:0.1rem 0.45rem;font-size:0.62rem;"><?= e($post['category']) ?></span></td>
      <td style="font-size:0.78rem;font-weight:700;"><?= $post['reply_count'] ?></td>
      <td style="font-size:0.78rem;"><?= $post['like_count'] ?></td>
      <td><button onclick="togglePost(<?=$post['id']?>)" style="padding:0.12rem 0.5rem;border-radius:999px;border:none;cursor:pointer;font-size:0.62rem;font-weight:700;background:<?=$post['is_active']?'rgba(34,197,94,0.12)':'rgba(239,68,68,0.1)'?>;color:<?=$post['is_active']?'#22C55E':'#EF4444'?>"><?=$post['is_active']?'Active':'Hidden'?></button></td>
      <td style="font-size:0.65rem;white-space:nowrap;"><?= date('d M Y',strtotime($post['created_at'])) ?></td>
      <td>
        <div style="display:flex;gap:0.3rem;">
          <a href="<?= url('community/post/'.$post['id']) ?>" target="_blank" style="padding:0.22rem 0.4rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.62rem;color:var(--kw-text-muted);text-decoration:none;"><i class="fa-solid fa-eye"></i></a>
          <button onclick="admDelete('admin/community',<?=$post['id']?>,()=>location.reload())" style="padding:0.22rem 0.4rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:4px;font-size:0.62rem;color:#EF4444;cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if(empty($posts)): ?>
    <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--kw-text-muted);font-size:0.78rem;">No community posts yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
  </div>
</div>
<script>
async function togglePost(id){
  const fd=new FormData();fd.append('action','toggle_post');fd.append('id',id);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r=await fetch(location.pathname,{method:'POST',body:fd});const d=await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error');if(d.success)setTimeout(()=>location.reload(),600);
}
</script>
<?php require_once __DIR__ . '/admin-footer.php'; ?>