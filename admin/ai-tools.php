<?php
$admin_title='Products'; $admin_active='products';
require_once __DIR__ . '/admin-header.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    header('Content-Type: application/json'); csrfAbortIfInvalid();
    if ($_POST['action']==='toggle_active') {
        $pdo->prepare('UPDATE products SET is_active=!is_active WHERE id=?')->execute([(int)$_POST['id']]);
        echo json_encode(['success'=>true,'message'=>'Product status toggled.']);
    } elseif ($_POST['action']==='save_product') {
        $id=(int)($_POST['product_id']??0); $name=trim(htmlspecialchars($_POST['name']??'',ENT_QUOTES,'UTF-8'));
        $slug=trim(preg_replace('/[^a-z0-9\-]/','-',strtolower($_POST['slug']??'')),'-');
        $desc=trim(htmlspecialchars($_POST['description']??'',ENT_QUOTES,'UTF-8'));
        $deploy=trim(htmlspecialchars($_POST['deployment_options']??'Cloud, On-premise, Hybrid',ENT_QUOTES,'UTF-8'));
        if(!$name){echo json_encode(['success'=>false,'message'=>'Name required.']);exit;}
        if(!$slug)$slug=preg_replace('/[^a-z0-9\-]/','-',strtolower($name));
        try {
            if($id) { $pdo->prepare('UPDATE products SET name=?,slug=?,description=?,deployment_options=? WHERE id=?')->execute([$name,$slug,$desc,$deploy,$id]); echo json_encode(['success'=>true,'message'=>'Updated.']); }
            else { $pdo->prepare('INSERT INTO products (name,slug,description,deployment_options,is_active) VALUES(?,?,?,?,1)')->execute([$name,$slug,$desc,$deploy]); echo json_encode(['success'=>true,'message'=>'Product created.']); }
        } catch(Exception $e){ echo json_encode(['success'=>false,'message'=>'Slug exists or DB error.']); }
    }
    exit;
}

$products = $pdo->query("SELECT p.*, (SELECT COUNT(*) FROM subscriptions WHERE product_id=p.id AND status='active') as active_subs FROM products ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Products</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);"><?= count($products) ?> enterprise systems</p></div>
  <button onclick="document.getElementById('new-prod-modal').style.display='flex'" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-plus"></i> Add Product</button>
</div>
<div class="adm-card" style="padding:0;overflow:hidden;">
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>Name</th><th>Slug</th><th>Active Subs</th><th>Deployment</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($products as $prod): ?>
    <tr>
      <td style="font-weight:700;font-size:0.82rem;"><?= e($prod['name']) ?></td>
      <td style="font-size:0.7rem;color:var(--kw-text-muted);"><?= e($prod['slug']) ?></td>
      <td><span style="background:rgba(34,197,94,0.1);color:#22C55E;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.65rem;font-weight:700;"><?= $prod['active_subs'] ?></span></td>
      <td style="font-size:0.72rem;"><?= e($prod['deployment_options']??'—') ?></td>
      <td>
        <button onclick="toggleProduct(<?= $prod['id'] ?>)" style="padding:0.15rem 0.55rem;border-radius:999px;border:none;cursor:pointer;font-size:0.65rem;font-weight:700;background:<?= $prod['is_active']?'rgba(34,197,94,0.12)':'rgba(107,114,128,0.12)' ?>;color:<?= $prod['is_active']?'#22C55E':'#6B7280' ?>;">
          <?= $prod['is_active']?'Active':'Inactive' ?>
        </button>
      </td>
      <td>
        <div style="display:flex;gap:0.3rem;">
          <a href="<?= url('products/'.$prod['slug']) ?>" target="_blank" style="padding:0.22rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-muted);text-decoration:none;"><i class="fa-solid fa-eye"></i></a>
          <button onclick="editProduct(<?= htmlspecialchars(json_encode($prod),ENT_QUOTES) ?>)" style="padding:0.22rem 0.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);cursor:pointer;">Edit</button>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
<!-- Product modal -->
<div id="new-prod-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;padding:1.5rem;" onclick="if(event.target===this)this.style.display='none'">
  <div style="background:var(--kw-bg-card);border-radius:var(--kw-radius-xl);width:100%;max-width:500px;padding:1.5rem;">
    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;"><h4 id="prod-modal-title">Add Product</h4><button onclick="document.getElementById('new-prod-modal').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--kw-text-muted);">✕</button></div>
    <form id="product-form">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="save_product">
      <input type="hidden" name="product_id" id="prod-id" value="0">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.75rem;">
        <div><label class="adm-label">Name *</label><input type="text" name="name" id="prod-name" class="adm-input" oninput="document.getElementById('prod-slug').value=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'')"></div>
        <div><label class="adm-label">Slug</label><input type="text" name="slug" id="prod-slug" class="adm-input"></div>
        <div style="grid-column:span 2;"><label class="adm-label">Description</label><textarea name="description" id="prod-desc" class="adm-input" rows="3"></textarea></div>
        <div style="grid-column:span 2;"><label class="adm-label">Deployment Options</label><input type="text" name="deployment_options" id="prod-deploy" class="adm-input" value="Cloud, On-premise, Hybrid"></div>
      </div>
      <div id="prod-alert"></div>
      <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('new-prod-modal').style.display='none'" class="kw-btn kw-btn-ghost kw-btn-sm">Cancel</button>
        <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" id="prod-btn"><i class="fa-solid fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>
<script>
function editProduct(prod) {
  document.getElementById('prod-modal-title').textContent='Edit: '+prod.name;
  document.getElementById('prod-id').value=prod.id;
  document.getElementById('prod-name').value=prod.name;
  document.getElementById('prod-slug').value=prod.slug;
  document.getElementById('prod-desc').value=prod.description||'';
  document.getElementById('prod-deploy').value=prod.deployment_options||'Cloud, On-premise, Hybrid';
  document.getElementById('new-prod-modal').style.display='flex';
}
async function toggleProduct(id) {
  const fd=new FormData(); fd.append('action','toggle_active'); fd.append('id',id);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r=await fetch(location.pathname,{method:'POST',body:fd}); const d=await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error'); if(d.success) setTimeout(()=>location.reload(),600);
}
document.getElementById('product-form').addEventListener('submit',async function(e){
  e.preventDefault(); const btn=document.getElementById('prod-btn'); btn.disabled=true;
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
  if(d.success){document.getElementById('new-prod-modal').style.display='none';window.Krest?.toast(d.message,'success');setTimeout(()=>location.reload(),600);}
  else document.getElementById('prod-alert').innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:0.75rem;">${d.message}</div>`;
  btn.disabled=false;
});
</script>
<?php require_once __DIR__ . '/admin-footer.php'; ?>