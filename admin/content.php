<?php
$admin_title  = 'Content & Blog';
$admin_active = 'content';
require_once __DIR__ . '/admin-header.php';
$pdo = db();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json'); csrfAbortIfInvalid();
    if ($_POST['action'] === 'save_post') {
        $id      = (int)($_POST['post_id']??0);
        $title   = trim(htmlspecialchars($_POST['title']??'', ENT_QUOTES,'UTF-8'));
        $slug    = trim(preg_replace('/[^a-z0-9\-]/','-',strtolower($_POST['slug']??'')),'-');
        $excerpt = trim(htmlspecialchars($_POST['excerpt']??'', ENT_QUOTES,'UTF-8'));
        $content = trim($_POST['content']??''); // sanitized on output
        $cat     = trim(htmlspecialchars($_POST['category']??'General', ENT_QUOTES,'UTF-8'));
        $pub     = isset($_POST['is_published'])?1:0;
        $pubAt   = $pub ? date('Y-m-d H:i:s') : null;
        if (!$title) { echo json_encode(['success'=>false,'message'=>'Title required.']); exit; }
        if (!$slug) $slug = preg_replace('/[^a-z0-9\-]/','-',strtolower($title));
        try {
            if ($id) { $pdo->prepare('UPDATE blog_posts SET title=?,slug=?,excerpt=?,content=?,category=?,is_published=?,published_at=COALESCE(published_at,?) WHERE id=?')->execute([$title,$slug,$excerpt,$content,$cat,$pub,$pubAt,$id]); echo json_encode(['success'=>true,'message'=>'Post updated.']); }
            else { $pdo->prepare('INSERT INTO blog_posts (title,slug,excerpt,content,category,is_published,published_at,author_id) VALUES(?,?,?,?,?,?,?,?)')->execute([$title,$slug,$excerpt,$content,$cat,$pub,$pubAt,$_SESSION['user_id']]); echo json_encode(['success'=>true,'message'=>'Post created.','id'=>$pdo->lastInsertId()]); }
        } catch(Exception $e){ echo json_encode(['success'=>false,'message'=>'Slug conflict or DB error.']); }
    } elseif ($_POST['action']==='toggle_publish') {
        $id=(int)$_POST['id'];
        $cur=$pdo->prepare('SELECT is_published FROM blog_posts WHERE id=?'); $cur->execute([$id]); $row=$cur->fetch();
        if($row){ $new=!$row['is_published']; $pdo->prepare('UPDATE blog_posts SET is_published=?,published_at=IF(?,NOW(),published_at) WHERE id=?')->execute([$new,$new,$id]); echo json_encode(['success'=>true,'message'=>$new?'Published.':'Unpublished.']); }
        else echo json_encode(['success'=>false,'message'=>'Post not found.']);
    } elseif ($_POST['action']==='delete_post') {
        $pdo->prepare('DELETE FROM blog_posts WHERE id=?')->execute([(int)$_POST['id']]); echo json_encode(['success'=>true,'message'=>'Post deleted.']);
    }
    exit;
}

$tab = $_GET['tab'] ?? 'posts';
$editId = (int)($_GET['edit']??0);
$editPost = null;
if ($editId) { $ep=$pdo->prepare('SELECT * FROM blog_posts WHERE id=?'); $ep->execute([$editId]); $editPost=$ep->fetch(PDO::FETCH_ASSOC); }

$posts = $pdo->query("SELECT bp.*,u.name as author_name FROM blog_posts bp LEFT JOIN users u ON u.id=bp.author_id ORDER BY bp.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$totalPublished = count(array_filter($posts,fn($p)=>$p['is_published']));

$categories = ['AI & Technology','Digital Transformation','Architecture','Development','Enterprise Software','Automation','Cloud & Infrastructure','Cybersecurity','General'];
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Content & Blog</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);"><?= count($posts) ?> posts · <?= $totalPublished ?> published</p></div>
  <button onclick="showPostForm()" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-plus"></i> New Post</button>
</div>

<div style="display:grid;grid-template-columns:1fr <?= $editPost ? '420px' : '' ?>;gap:1.25rem;">

  <div class="adm-card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
    <table class="adm-table">
      <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($posts as $post): ?>
      <tr>
        <td>
          <div style="font-weight:700;font-size:0.78rem;"><?= e($post['title']) ?></div>
          <div style="font-size:0.62rem;color:var(--kw-text-muted);">/blog/<?= e($post['slug']) ?></div>
        </td>
        <td style="font-size:0.72rem;"><?= e($post['category']??'General') ?></td>
        <td style="font-size:0.72rem;"><?= e($post['author_name']??'Admin') ?></td>
        <td>
          <button onclick="togglePublish(<?= $post['id'] ?>)" style="padding:0.15rem 0.55rem;border-radius:999px;border:none;font-size:0.65rem;font-weight:700;cursor:pointer;background:<?= $post['is_published']?'rgba(34,197,94,0.12)':'rgba(107,114,128,0.12)' ?>;color:<?= $post['is_published']?'#22C55E':'#6B7280' ?>;">
            <?= $post['is_published']?'Published':'Draft' ?>
          </button>
        </td>
        <td style="font-size:0.68rem;white-space:nowrap;"><?= date('d M Y',strtotime($post['created_at'])) ?></td>
        <td>
          <div style="display:flex;gap:0.3rem;">
            <a href="?edit=<?= $post['id'] ?>" style="padding:0.22rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);text-decoration:none;">Edit</a>
            <a href="<?= url('blog/'.$post['slug']) ?>" target="_blank" style="padding:0.22rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-muted);text-decoration:none;" title="View live"><i class="fa-solid fa-eye"></i></a>
            <button onclick="admDelete('admin/content',<?= $post['id'] ?>,()=>location.reload())" style="padding:0.22rem 0.5rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:4px;font-size:0.65rem;color:#EF4444;cursor:pointer;" onclick="event.target.dataset.action='delete_post';"><i class="fa-solid fa-trash"></i></button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($posts)): ?><tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--kw-text-muted);font-size:0.78rem;">No blog posts yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
    </div>
  </div>

  <?php if ($editPost): ?>
  <div class="adm-card" style="padding:1.25rem;align-self:flex-start;position:sticky;top:calc(var(--adm-topbar)+0.75rem);max-height:85vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <h4 style="font-size:0.9rem;margin:0;">Edit Post</h4>
      <a href="?" style="font-size:0.7rem;color:var(--kw-text-muted);">✕ Close</a>
    </div>
    <form id="edit-post-form">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="save_post">
      <input type="hidden" name="post_id" value="<?= $editPost['id'] ?>">
      <div style="margin-bottom:0.65rem;"><label class="adm-label">Title</label><input type="text" name="title" class="adm-input" value="<?= e($editPost['title']) ?>"></div>
      <div style="margin-bottom:0.65rem;"><label class="adm-label">Slug</label><input type="text" name="slug" class="adm-input" value="<?= e($editPost['slug']) ?>"></div>
      <div style="margin-bottom:0.65rem;"><label class="adm-label">Category</label>
        <select name="category" class="adm-input adm-select">
          <?php foreach($categories as $c): ?><option <?= $editPost['category']===$c?'selected':'' ?>><?= $c ?></option><?php endforeach; ?>
        </select>
      </div>
      <div style="margin-bottom:0.65rem;"><label class="adm-label">Excerpt</label><textarea name="excerpt" class="adm-input" rows="2"><?= e($editPost['excerpt']??'') ?></textarea></div>
      <div style="margin-bottom:0.75rem;"><label class="adm-label">Content (HTML)</label><textarea name="content" class="adm-input" rows="10" style="font-family:monospace;font-size:0.75rem;"><?= e($editPost['content']??'') ?></textarea></div>
      <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;cursor:pointer;margin-bottom:1rem;"><input type="checkbox" name="is_published" <?= $editPost['is_published']?'checked':'' ?> style="accent-color:var(--kw-primary);"> Publish immediately</label>
      <div id="post-save-alert"></div>
      <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" style="width:100%;justify-content:center;"><i class="fa-solid fa-save"></i> Save Post</button>
    </form>
  </div>
  <?php endif; ?>

</div>

<!-- New post modal -->
<div id="new-post-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;padding:1.5rem;" onclick="if(event.target===this)this.style.display='none'">
  <div style="background:var(--kw-bg-card);border-radius:var(--kw-radius-xl);width:100%;max-width:580px;padding:1.5rem;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;">
      <h4>New Blog Post</h4>
      <button onclick="document.getElementById('new-post-modal').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--kw-text-muted);">✕</button>
    </div>
    <form id="new-post-form">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="save_post">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.75rem;">
        <div><label class="adm-label">Title *</label><input type="text" name="title" class="adm-input" placeholder="Post title" oninput="this.form.slug.value=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'')"></div>
        <div><label class="adm-label">Slug</label><input type="text" name="slug" class="adm-input" placeholder="auto-generated"></div>
        <div><label class="adm-label">Category</label><select name="category" class="adm-input adm-select"><?php foreach($categories as $c): ?><option><?=$c?></option><?php endforeach; ?></select></div>
        <div style="display:flex;align-items:flex-end;padding-bottom:0.2rem;"><label style="display:flex;align-items:center;gap:0.45rem;font-size:0.78rem;cursor:pointer;"><input type="checkbox" name="is_published" style="accent-color:var(--kw-primary);"> Publish now</label></div>
      </div>
      <div style="margin-bottom:0.75rem;"><label class="adm-label">Excerpt</label><textarea name="excerpt" class="adm-input" rows="2" placeholder="Brief summary…"></textarea></div>
      <div style="margin-bottom:1rem;"><label class="adm-label">Content</label><textarea name="content" class="adm-input" rows="8" placeholder="<h2>Introduction</h2><p>…</p>" style="font-family:monospace;font-size:0.78rem;"></textarea></div>
      <div id="new-post-alert"></div>
      <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('new-post-modal').style.display='none'" class="kw-btn kw-btn-ghost kw-btn-sm">Cancel</button>
        <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" id="new-post-btn"><i class="fa-solid fa-save"></i> Create Post</button>
      </div>
    </form>
  </div>
</div>

<script>
function showPostForm() { document.getElementById('new-post-modal').style.display='flex'; document.body.style.overflow='hidden'; }
async function togglePublish(id) {
  const fd=new FormData(); fd.append('action','toggle_publish'); fd.append('id',id);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r=await fetch(location.pathname,{method:'POST',body:fd}); const d=await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error'); if(d.success) setTimeout(()=>location.reload(),700);
}
document.getElementById('edit-post-form')?.addEventListener('submit',async function(e){
  e.preventDefault(); const btn=this.querySelector('[type=submit]'); btn.disabled=true;
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
  document.getElementById('post-save-alert').innerHTML=`<div class="kw-alert kw-alert-${d.success?'success':'danger'}" style="margin-bottom:0.75rem;">${d.message}</div>`;
  btn.disabled=false;
});
document.getElementById('new-post-form')?.addEventListener('submit',async function(e){
  e.preventDefault(); const btn=document.getElementById('new-post-btn'); btn.disabled=true; btn.textContent='Saving…';
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
  if(d.success){ document.getElementById('new-post-modal').style.display='none'; window.Krest?.toast(d.message,'success'); document.body.style.overflow=''; setTimeout(()=>location.reload(),600); }
  else{ document.getElementById('new-post-alert').innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:0.75rem;">${d.message}</div>`; }
  btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-save"></i> Create Post';
});
</script>
<?php require_once __DIR__ . '/admin-footer.php'; ?>