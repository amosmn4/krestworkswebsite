<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Recorded Demos — ' . APP_NAME;
$page_description = 'Watch self-paced recorded walkthroughs of all Krestworks enterprise systems. No registration required for basic access.';


$recordings = [
  ['hr-system','fa-users','#F5A800','HR Management System','Full walkthrough: employee records, payroll, leave, performance, and analytics.','32 min','All HR teams'],
  ['procurement-system','fa-boxes-stacking','#3B82F6','Procurement Management','Supplier management, purchase workflows, invoice processing, and approval automation.','28 min','Finance & Procurement'],
  ['elearning-system','fa-graduation-cap','#22C55E','eLearning Management System','Course management, student portal, exams, grading, and certifications.','25 min','Education institutions'],
  ['real-estate-system','fa-building','#A855F7','Real Estate Management','Property, tenant, lease, rent automation, and maintenance tracking.','30 min','Property managers'],
  ['supply-chain-system','fa-truck','#F97316','Supply Chain Management','Inventory, logistics, warehouse management, and order processing.','27 min','Logistics teams'],
  ['decision-support-system','fa-chart-bar','#EF4444','Executive Decision Support','Business analytics, KPI dashboards, and AI-powered insights.','20 min','C-Suite & Management'],
  ['crm-system','fa-handshake','#06B6D4','CRM System','Lead management, customer records, pipeline tracking, and sales automation.','24 min','Sales teams'],
  ['hospital-system','fa-hospital','#8B5CF6','Hospital Management System','Patient records, appointment scheduling, billing, and clinical workflows.','35 min','Healthcare providers'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('demo') ?>">Demo Center</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Recorded Demos</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label" style="background:rgba(59,130,246,0.15);color:#3B82F6;"><i class="fa-solid fa-film"></i> On-Demand</span>
      <h1>Recorded Product Demos</h1>
      <p style="color:rgba(255,255,255,0.65);max-width:540px;">Watch comprehensive walkthroughs of our enterprise systems at your own pace. Pause, replay, and share with your team. No registration required for public demos.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">

    <!-- Filter bar -->
    <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:2rem;flex-wrap:wrap;" data-aos="fade-up">
      <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);">Filter:</span>
      <button class="rec-filter active" data-filter="all" onclick="filterRecordings('all',this)" style="padding:0.3rem 0.85rem;border-radius:999px;border:1px solid var(--kw-primary);background:var(--kw-primary)15;color:var(--kw-primary);font-size:0.75rem;font-weight:700;cursor:pointer;">All Systems</button>
      <?php foreach ([['HR & People','hr-system'],['Finance','procurement-system'],['Education','elearning-system'],['Real Estate','real-estate-system'],['Healthcare','hospital-system'],['Sales','crm-system']] as $f): ?>
      <button class="rec-filter" data-filter="<?= $f[1] ?>" onclick="filterRecordings('<?= $f[1] ?>',this)" style="padding:0.3rem 0.85rem;border-radius:999px;border:1px solid var(--kw-border);background:none;color:var(--kw-text-muted);font-size:0.75rem;font-weight:600;cursor:pointer;transition:all 0.2s;"><?= $f[0] ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Recordings grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem;" id="recordings-grid">
      <?php foreach ($recordings as $i => $rec): ?>
      <div class="kw-card rec-card" data-system="<?= $rec[0] ?>" style="padding:0;overflow:hidden;transition:all 0.25s;cursor:pointer;"
           onclick="playDemo('<?= $rec[0] ?>','<?= addslashes($rec[3]) ?>')"
           onmouseover="this.style.transform='translateY(-4px)';this.querySelector('.play-overlay').style.opacity='1'"
           onmouseout="this.style.transform='';this.querySelector('.play-overlay').style.opacity='0'"
           data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 60 ?>">

        <!-- Thumbnail -->
        <div style="position:relative;background:linear-gradient(135deg,<?= $rec[2] ?>20,<?= $rec[2] ?>08);height:160px;display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--kw-border);">
          <i class="fa-solid <?= $rec[1] ?>" style="font-size:3rem;color:<?= $rec[2] ?>;opacity:0.4;"></i>
          <div class="play-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;">
            <div style="width:56px;height:56px;border-radius:50%;background:var(--kw-primary);display:flex;align-items:center;justify-content:center;">
              <i class="fa-solid fa-play" style="color:#0A0F1A;font-size:1.2rem;margin-left:3px;"></i>
            </div>
          </div>
          <!-- Duration badge -->
          <div style="position:absolute;bottom:0.65rem;right:0.65rem;background:rgba(0,0,0,0.75);color:#fff;border-radius:4px;padding:0.15rem 0.5rem;font-size:0.7rem;font-weight:700;">
            <i class="fa-solid fa-clock" style="font-size:0.6rem;margin-right:0.2rem;"></i><?= $rec[5] ?>
          </div>
        </div>

        <div style="padding:1.25rem;">
          <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
            <i class="fa-solid <?= $rec[1] ?>" style="color:<?= $rec[2] ?>;font-size:0.85rem;"></i>
            <span style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:<?= $rec[2] ?>;"><?= $rec[6] ?></span>
          </div>
          <h4 style="font-size:0.95rem;margin-bottom:0.35rem;"><?= $rec[3] ?></h4>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);line-height:1.5;margin-bottom:0.85rem;"><?= $rec[4] ?></p>
          <div style="display:flex;gap:0.5rem;">
            <button style="flex:1;padding:0.45rem;background:var(--kw-primary);color:#0A0F1A;border:none;border-radius:var(--kw-radius-md);font-weight:700;font-size:0.75rem;cursor:pointer;">
              <i class="fa-solid fa-play"></i> Watch Now
            </button>
            <a href="<?= url('demo') ?>#book-form" onclick="event.stopPropagation();" style="padding:0.45rem 0.65rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.75rem;color:var(--kw-text-muted);text-decoration:none;white-space:nowrap;" title="Book live demo">
              <i class="fa-solid fa-calendar-check"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Premium CTA -->
    <div class="kw-card" style="padding:2rem;margin-top:2.5rem;text-align:center;border-top:3px solid var(--kw-primary);" data-aos="fade-up">
      <h3 style="margin-bottom:0.5rem;">Want a Personalised Walkthrough?</h3>
      <p style="color:var(--kw-text-muted);margin-bottom:1.5rem;max-width:480px;margin-left:auto;margin-right:auto;">Recordings show standard features. A live demo is configured around your specific workflow, industry, and team size.</p>
      <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
        <a href="<?= url('demo/live') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-video"></i> Book a Live Demo</a>
        <a href="<?= url('demo/sandbox') ?>" class="kw-btn kw-btn-ghost kw-btn-lg"><i class="fa-solid fa-flask"></i> Try Sandbox</a>
      </div>
    </div>

  </div>
</section>

<!-- Video modal -->
<div id="video-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;display:none;align-items:center;justify-content:center;padding:1.5rem;" onclick="if(event.target===this)closeDemo()">
  <div style="background:var(--kw-bg-card);border-radius:var(--kw-radius-xl);overflow:hidden;width:100%;max-width:820px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid var(--kw-border);">
      <h4 id="video-title" style="font-size:0.95rem;margin:0;"></h4>
      <button onclick="closeDemo()" style="background:none;border:none;color:var(--kw-text-muted);font-size:1.25rem;cursor:pointer;">✕</button>
    </div>
    <div style="padding:2rem;text-align:center;">
      <div style="width:80px;height:80px;border-radius:50%;background:var(--kw-primary)20;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
        <i class="fa-solid fa-film" style="font-size:2rem;color:var(--kw-primary);"></i>
      </div>
      <h3 style="margin-bottom:0.5rem;" id="video-modal-product"></h3>
      <p style="color:var(--kw-text-muted);font-size:0.875rem;margin-bottom:1.5rem;">
        Full recorded demo video will be available here. To access the complete recording library, please provide your work email below.
      </p>
      <div style="display:flex;gap:0.5rem;max-width:400px;margin:0 auto 1rem;">
        <input type="email" id="rec-email" placeholder="Work email address" style="flex:1;padding:0.6rem 0.85rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;" onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
        <button onclick="sendRecordingLink()" class="kw-btn kw-btn-primary" style="white-space:nowrap;"><i class="fa-solid fa-paper-plane"></i> Send Link</button>
      </div>
      <p style="font-size:0.72rem;color:var(--kw-text-muted);">Or <a href="<?= url('demo/live') ?>" style="color:var(--kw-primary);">book a live demo</a> to see it in real time.</p>
    </div>
  </div>
</div>

<script>
function filterRecordings(filter, btn) {
  document.querySelectorAll('.rec-filter').forEach(b => {
    b.style.borderColor = '';
    b.style.background = 'none';
    b.style.color = 'var(--kw-text-muted)';
  });
  btn.style.borderColor = 'var(--kw-primary)';
  btn.style.background = 'var(--kw-primary)15';
  btn.style.color = 'var(--kw-primary)';
  document.querySelectorAll('.rec-card').forEach(card => {
    card.style.display = (filter === 'all' || card.dataset.system === filter) ? '' : 'none';
  });
}
function playDemo(slug, title) {
  document.getElementById('video-modal').style.display = 'flex';
  document.getElementById('video-title').textContent = 'Demo: ' + title;
  document.getElementById('video-modal-product').textContent = title;
  document.body.style.overflow = 'hidden';
}
function closeDemo() {
  document.getElementById('video-modal').style.display = 'none';
  document.body.style.overflow = '';
}
function sendRecordingLink() {
  const email = document.getElementById('rec-email').value;
  if (!email || !email.includes('@')) { alert('Please enter a valid email.'); return; }
  // Could submit to newsletter endpoint
  window.Krest?.toast('Recording link sent to ' + email, 'success');
  closeDemo();
}
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>