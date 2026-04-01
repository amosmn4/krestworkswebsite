<?php
$admin_title='Recruitment — Job Positions'; $admin_active='recruitment';
require_once __DIR__ . '/../admin-header.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    header('Content-Type: application/json'); csrfAbortIfInvalid();
    $action = $_POST['action'];
    try {
        if ($action==='save_job') {
            $id   = (int)($_POST['job_id']??0);
            $data = [
                'title'           => trim(htmlspecialchars($_POST['title']??'',ENT_QUOTES,'UTF-8')),
                'slug'            => trim(preg_replace('/[^a-z0-9\-]/','-',strtolower($_POST['slug']??'')),'-'),
                'department'      => trim(htmlspecialchars($_POST['department']??'',ENT_QUOTES,'UTF-8')),
                'employment_type' => in_array($_POST['employment_type']??'',['full-time','part-time','contract','internship','volunteer'])?$_POST['employment_type']:'full-time',
                'location'        => trim(htmlspecialchars($_POST['location']??'',ENT_QUOTES,'UTF-8')),
                'is_remote'       => isset($_POST['is_remote'])?1:0,
                'min_qualification'=> in_array($_POST['min_qualification']??'',['kcse','certificate','diploma','degree','masters','phd'])?$_POST['min_qualification']:'diploma',
                'experience_years'=> max(0,(int)($_POST['experience_years']??0)),
                'description'     => trim($_POST['description']??''),
                'responsibilities'=> trim($_POST['responsibilities']??''),
                'requirements'    => trim($_POST['requirements']??''),
                'benefits'        => trim($_POST['benefits']??''),
                'deadline'        => !empty($_POST['deadline'])?$_POST['deadline']:null,
                'is_active'       => isset($_POST['is_active'])?1:0,
                'is_featured'     => isset($_POST['is_featured'])?1:0,
                'show_salary'     => isset($_POST['show_salary'])?1:0,
                'salary_min'      => !empty($_POST['salary_min'])?(float)$_POST['salary_min']:null,
                'salary_max'      => !empty($_POST['salary_max'])?(float)$_POST['salary_max']:null,
                'created_by'      => $_SESSION['user_id'],
            ];
            if (!$data['title']) throw new Exception('Title required.');
            if (!$data['slug']) $data['slug']=preg_replace('/[^a-z0-9\-]/','-',strtolower($data['title']));
            if ($id) {
                $pdo->prepare("UPDATE job_positions SET ".implode(',',array_map(fn($k)=>"$k=?",array_keys($data)))." WHERE id=?")->execute(array_merge(array_values($data),[$id]));
                echo json_encode(['success'=>true,'message'=>'Position updated.']);
            } else {
                $pdo->prepare("INSERT INTO job_positions (".implode(',',array_keys($data)).") VALUES (".implode(',',array_fill(0,count($data),'?')).")")->execute(array_values($data));
                echo json_encode(['success'=>true,'message'=>'Position created.','id'=>$pdo->lastInsertId()]);
            }
        } elseif ($action==='toggle_active') {
            $pdo->prepare('UPDATE job_positions SET is_active=!is_active WHERE id=?')->execute([(int)$_POST['id']]);
            echo json_encode(['success'=>true,'message'=>'Status toggled.']);
        } elseif ($action==='delete_job') {
            $pdo->prepare('DELETE FROM job_positions WHERE id=?')->execute([(int)$_POST['id']]);
            echo json_encode(['success'=>true,'message'=>'Position deleted.']);
        }
    } catch(Exception $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>$e->getMessage()]); }
    exit;
}

$jobs = $pdo->query("SELECT jp.*,(SELECT COUNT(*) FROM job_applications WHERE job_position_id=jp.id AND status!='draft') as app_count FROM job_positions jp ORDER BY jp.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$qualLabels=['kcse'=>'KCSE','certificate'=>'Cert','diploma'=>'Diploma','degree'=>'Degree','masters'=>'Masters','phd'=>'PhD'];
$typeColors=['full-time'=>'#22C55E','part-time'=>'#3B82F6','contract'=>'#F5A800','internship'=>'#A855F7','volunteer'=>'#F97316'];
?>
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
  <div><h1 style="font-size:1.1rem;margin:0;">Job Positions</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);"><?= count($jobs) ?> positions</p></div>
  <div style="display:flex;gap:0.5rem;">
    <a href="<?= url('admin/recruitment/reports') ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    <button onclick="document.getElementById('job-modal').style.display='flex';document.getElementById('modal-title').textContent='New Position';document.getElementById('job-form').reset();document.getElementById('job-id').value=0;" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-plus"></i> New Position</button>
  </div>
</div>

<div class="adm-card" style="padding:0;overflow:hidden;">
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>Title</th><th>Dept</th><th>Type</th><th>Min Qual</th><th>Deadline</th><th>Applications</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($jobs as $job):
      $c = $typeColors[$job['employment_type']] ?? '#6B7280';
      $deadlinePassed = $job['deadline'] && strtotime($job['deadline']) < time();
    ?>
    <tr>
      <td>
        <div style="font-weight:700;font-size:0.82rem;"><?= e($job['title']) ?></div>
        <div style="font-size:0.62rem;color:var(--kw-text-muted);">/careers/<?= e($job['slug']) ?></div>
      </td>
      <td style="font-size:0.75rem;"><?= e($job['department']) ?></td>
      <td><span style="background:<?=$c?>15;color:<?=$c?>;border-radius:999px;padding:0.12rem 0.5rem;font-size:0.62rem;font-weight:700;"><?= ucfirst(str_replace('-',' ',$job['employment_type'])) ?></span><?= $job['is_remote']?'<span style="background:rgba(59,130,246,0.1);color:#3B82F6;border-radius:999px;padding:0.1rem 0.4rem;font-size:0.6rem;margin-left:0.2rem;font-weight:700;">Remote</span>':'' ?></td>
      <td style="font-size:0.72rem;"><?= $qualLabels[$job['min_qualification']] ?? $job['min_qualification'] ?></td>
      <td style="font-size:0.72rem;color:<?= $deadlinePassed?'#EF4444':'var(--kw-text-muted)' ?>;"><?= $job['deadline'] ? date('d M Y',strtotime($job['deadline'])) : 'Open' ?></td>
      <td>
        <a href="<?= url('admin/recruitment/applications') ?>?job=<?= $job['id'] ?>" style="background:rgba(245,168,0,0.12);color:var(--kw-primary);border-radius:999px;padding:0.12rem 0.55rem;font-size:0.7rem;font-weight:800;text-decoration:none;"><?= $job['app_count'] ?></a>
      </td>
      <td>
        <button onclick="toggleJob(<?= $job['id'] ?>)" style="padding:0.15rem 0.55rem;border-radius:999px;border:none;cursor:pointer;font-size:0.62rem;font-weight:700;background:<?= $job['is_active']?'rgba(34,197,94,0.12)':'rgba(107,114,128,0.12)' ?>;color:<?= $job['is_active']?'#22C55E':'#6B7280' ?>;"><?= $job['is_active']?'Active':'Inactive' ?></button>
        <?= $job['is_featured']?'<span style="background:rgba(245,168,0,0.1);color:var(--kw-primary);border-radius:999px;padding:0.1rem 0.4rem;font-size:0.6rem;font-weight:700;margin-left:0.2rem;">Featured</span>':'' ?>
      </td>
      <td>
        <div style="display:flex;gap:0.3rem;">
          <a href="<?= url('careers/'.$job['slug']) ?>" target="_blank" style="padding:0.22rem 0.45rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-muted);text-decoration:none;"><i class="fa-solid fa-eye"></i></a>
          <button onclick="editJob(<?= htmlspecialchars(json_encode($job),ENT_QUOTES) ?>)" style="padding:0.22rem 0.45rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);cursor:pointer;">Edit</button>
          <button onclick="admDelete('admin/recruitment/jobs',<?= $job['id'] ?>,()=>location.reload())" style="padding:0.22rem 0.45rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:4px;font-size:0.65rem;color:#EF4444;cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if(empty($jobs)):<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--kw-text-muted);font-size:0.78rem;">No positions yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
</div>

<!-- Job Modal -->
<div id="job-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9999;align-items:center;justify-content:center;padding:1.5rem;" onclick="if(event.target===this)this.style.display='none'">
  <div style="background:var(--kw-bg-card);border-radius:var(--kw-radius-xl);width:100%;max-width:680px;max-height:90vh;overflow-y:auto;">
    <div style="padding:1.1rem 1.25rem;border-bottom:1px solid var(--kw-border);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:var(--kw-bg-card);z-index:1;">
      <h4 id="modal-title" style="margin:0;font-size:0.95rem;">New Position</h4>
      <button onclick="document.getElementById('job-modal').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--kw-text-muted);">✕</button>
    </div>
    <form id="job-form" style="padding:1.25rem;">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="save_job">
      <input type="hidden" name="job_id" id="job-id" value="0">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:0.85rem;">
        <div><label class="adm-label">Title *</label><input type="text" name="title" class="adm-input" required oninput="document.getElementById('slug-f').value=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'')"></div>
        <div><label class="adm-label">Slug</label><input type="text" name="slug" id="slug-f" class="adm-input"></div>
        <div><label class="adm-label">Department</label><input type="text" name="department" class="adm-input" placeholder="Engineering"></div>
        <div><label class="adm-label">Employment Type</label><select name="employment_type" class="adm-input adm-select"><option value="full-time">Full Time</option><option value="part-time">Part Time</option><option value="contract">Contract</option><option value="internship">Internship</option><option value="volunteer">Volunteer</option></select></div>
        <div><label class="adm-label">Location</label><input type="text" name="location" class="adm-input" placeholder="Nairobi, Kenya"></div>
        <div><label class="adm-label">Min. Qualification</label><select name="min_qualification" class="adm-input adm-select"><option value="kcse">KCSE</option><option value="certificate">Certificate</option><option value="diploma">Diploma</option><option value="degree">Degree</option><option value="masters">Masters</option><option value="phd">PhD</option></select></div>
        <div><label class="adm-label">Experience (years)</label><input type="number" name="experience_years" class="adm-input" min="0" value="0"></div>
        <div><label class="adm-label">Application Deadline</label><input type="date" name="deadline" class="adm-input" min="<?= date('Y-m-d') ?>"></div>
        <div><label class="adm-label">Salary Min (KES)</label><input type="number" name="salary_min" class="adm-input" placeholder="50000"></div>
        <div><label class="adm-label">Salary Max (KES)</label><input type="number" name="salary_max" class="adm-input" placeholder="100000"></div>
        <div style="grid-column:span 2;display:flex;gap:1.25rem;flex-wrap:wrap;">
          <?php foreach([['is_remote','Remote OK'],['is_active','Active / Visible'],['is_featured','Featured'],['show_salary','Show Salary']] as $chk): ?>
          <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;cursor:pointer;"><input type="checkbox" name="<?=$chk[0]?>" value="1" style="accent-color:var(--kw-primary);"> <?=$chk[1]?></label>
          <?php endforeach; ?>
        </div>
        <div style="grid-column:span 2;"><label class="adm-label">Description</label><textarea name="description" class="adm-input" rows="3"></textarea></div>
        <div style="grid-column:span 2;"><label class="adm-label">Responsibilities (one per line)</label><textarea name="responsibilities" class="adm-input" rows="4"></textarea></div>
        <div style="grid-column:span 2;"><label class="adm-label">Requirements (one per line)</label><textarea name="requirements" class="adm-input" rows="4"></textarea></div>
        <div style="grid-column:span 2;"><label class="adm-label">Benefits (one per line)</label><textarea name="benefits" class="adm-input" rows="3"></textarea></div>
      </div>
      <div id="job-modal-alert"></div>
      <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('job-modal').style.display='none'" class="kw-btn kw-btn-ghost">Cancel</button>
        <button type="submit" class="kw-btn kw-btn-primary" id="job-save-btn"><i class="fa-solid fa-save"></i> Save Position</button>
      </div>
    </form>
  </div>
</div>
<script>
function editJob(j) {
  document.getElementById('modal-title').textContent = 'Edit: ' + j.title;
  const f = document.getElementById('job-form');
  f.reset();
  document.getElementById('job-id').value = j.id;
  ['title','slug','department','employment_type','location','min_qualification','experience_years','deadline','salary_min','salary_max','description','responsibilities','requirements','benefits'].forEach(k => { const el=f.querySelector(`[name="${k}"]`); if(el) el.value=j[k]||''; });
  ['is_remote','is_active','is_featured','show_salary'].forEach(k => { const el=f.querySelector(`[name="${k}"]`); if(el) el.checked=j[k]==='1'||j[k]===1; });
  document.getElementById('job-modal').style.display='flex';
}
async function toggleJob(id){
  const fd=new FormData();fd.append('action','toggle_active');fd.append('id',id);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r=await fetch(location.pathname,{method:'POST',body:fd});const d=await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error');if(d.success)setTimeout(()=>location.reload(),600);
}
document.getElementById('job-form').addEventListener('submit',async function(e){
  e.preventDefault();const btn=document.getElementById('job-save-btn');btn.disabled=true;
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)});const d=await r.json();
  if(d.success){document.getElementById('job-modal').style.display='none';window.Krest?.toast(d.message,'success');setTimeout(()=>location.reload(),600);}
  else document.getElementById('job-modal-alert').innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:0.75rem;">${d.message}</div>`;
  btn.disabled=false;
});
</script>
<style>@media(max-width:640px){#job-form div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}div[style*="grid-column:span 2"]{grid-column:span 1!important;}}</style>
<?php require_once __DIR__ . '/../admin-footer.php'; ?>
