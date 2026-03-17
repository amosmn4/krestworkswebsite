<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Sandbox Access — ' . APP_NAME;
$page_description = 'Get 7-day hands-on sandbox access to any Krestworks enterprise system. Explore all features yourself with test data pre-loaded.';


$sandboxProducts = [
  ['hr-system','fa-users','#F5A800','HR Management System','Pre-loaded with 50 test employees, payroll cycles, and leave records.','7 days'],
  ['procurement-system','fa-boxes-stacking','#3B82F6','Procurement Management','Active suppliers, purchase orders, and approval workflows ready to test.','7 days'],
  ['elearning-system','fa-graduation-cap','#22C55E','eLearning Management System','3 sample courses, enrolled students, and grade records.','7 days'],
  ['real-estate-system','fa-building','#A855F7','Real Estate Management','10 properties, tenants, leases, and maintenance tickets loaded.','7 days'],
  ['crm-system','fa-handshake','#06B6D4','CRM System','100 leads, deals, and customer records pre-populated.','7 days'],
  ['decision-support-system','fa-chart-bar','#EF4444','Executive Decision Support','Dashboard with 12 months of sample KPI data and AI insights.','7 days'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('demo') ?>">Demo Center</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Sandbox Access</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label" style="background:rgba(34,197,94,0.15);color:#22C55E;"><i class="fa-solid fa-flask"></i> Hands-On Access</span>
      <h1>Sandbox Access</h1>
      <p style="color:rgba(255,255,255,0.65);max-width:540px;">Get 7-day read/write access to a real test environment. Explore every feature, create records, run workflows — all with pre-loaded sample data. No commitment, no risk.</p>
      <div style="display:flex;gap:1.25rem;margin-top:1.5rem;flex-wrap:wrap;">
        <?php foreach ([['fa-database','Pre-loaded test data'],['fa-clock','7-day access'],['fa-users','Up to 3 users'],['fa-undo','Resets daily at midnight']] as $d): ?>
        <div style="display:flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.08);border-radius:999px;padding:0.4rem 1rem;font-size:0.78rem;color:rgba(255,255,255,0.75);">
          <i class="fa-solid <?= $d[0] ?>" style="color:#22C55E;font-size:0.7rem;"></i> <?= $d[1] ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Rules & what's included -->
<section style="background:var(--kw-bg-alt);padding:3rem 0;border-bottom:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;" data-aos="fade-up">
      <div>
        <h3 style="margin-bottom:1.25rem;">What's Included</h3>
        <?php foreach ([
          ['fa-check-circle','#22C55E','Full system access','All modules, all features — nothing locked or hidden.'],
          ['fa-database','#3B82F6','Pre-loaded sample data','Realistic test records so you can explore immediately.'],
          ['fa-users','#A855F7','Up to 3 test accounts','Test different user roles — admin, manager, and staff.'],
          ['fa-headset','#F5A800','Support chat','Our team is available during your sandbox period for questions.'],
          ['fa-file-export','#F97316','Export testing','Test data exports — CSV, Excel, PDF — to validate output formats.'],
        ] as $item): ?>
        <div style="display:flex;gap:0.75rem;padding:0.75rem 0;border-bottom:1px solid var(--kw-border);">
          <i class="fa-solid <?= $item[0] ?>" style="color:<?= $item[1] ?>;flex-shrink:0;margin-top:0.15rem;font-size:0.9rem;"></i>
          <div>
            <div style="font-size:0.875rem;font-weight:700;"><?= $item[2] ?></div>
            <div style="font-size:0.78rem;color:var(--kw-text-muted);"><?= $item[3] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div>
        <h3 style="margin-bottom:1.25rem;">Sandbox Limitations</h3>
        <?php foreach ([
          ['fa-times-circle','#EF4444','No real data','Sandbox uses test data only. Never upload real customer/employee data.'],
          ['fa-times-circle','#EF4444','No email sending','Email functionality is disabled to prevent accidental sends.'],
          ['fa-times-circle','#EF4444','Daily reset','All data resets at midnight EAT each day. Export before midnight if needed.'],
          ['fa-times-circle','#EF4444','3 concurrent users max','Sandbox is for evaluation only, not team training.'],
          ['fa-info-circle','#F5A800','Need more time?','Contact us to extend your sandbox period or book a live demo.'],
        ] as $item): ?>
        <div style="display:flex;gap:0.75rem;padding:0.75rem 0;border-bottom:1px solid var(--kw-border);">
          <i class="fa-solid <?= $item[0] ?>" style="color:<?= $item[1] ?>;flex-shrink:0;margin-top:0.15rem;font-size:0.9rem;"></i>
          <div>
            <div style="font-size:0.875rem;font-weight:700;"><?= $item[2] ?></div>
            <div style="font-size:0.78rem;color:var(--kw-text-muted);"><?= $item[3] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Available sandboxes -->
<section style="background:var(--kw-bg);padding:3rem 0;">
  <div class="kw-container">
    <div style="text-align:center;margin-bottom:2rem;" data-aos="fade-up">
      <h2>Available Sandbox Environments</h2>
      <p style="color:var(--kw-text-muted);">Request access to any of the systems below. Credentials are emailed within 2 hours.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;margin-bottom:3rem;">
      <?php foreach ($sandboxProducts as $i => $sp): ?>
      <div class="kw-card" style="padding:1.5rem;border-top:3px solid <?= $sp[2] ?>;" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 60 ?>">
        <div style="display:flex;align-items:center;gap:0.85rem;margin-bottom:1rem;">
          <div style="width:44px;height:44px;border-radius:10px;background:<?= $sp[2] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid <?= $sp[1] ?>" style="font-size:1.1rem;color:<?= $sp[2] ?>;"></i>
          </div>
          <div>
            <div style="font-size:0.875rem;font-weight:700;"><?= $sp[3] ?></div>
            <span style="background:<?= $sp[2] ?>15;color:<?= $sp[2] ?>;border-radius:999px;padding:0.1rem 0.5rem;font-size:0.65rem;font-weight:700;"><?= $sp[5] ?> Free Access</span>
          </div>
        </div>
        <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1rem;line-height:1.5;"><?= $sp[4] ?></p>
        <button onclick="requestSandbox('<?= $sp[0] ?>','<?= addslashes($sp[3]) ?>')" class="kw-btn kw-btn-sm" style="width:100%;justify-content:center;border-color:<?= $sp[2] ?>50;color:<?= $sp[2] ?>;">
          <i class="fa-solid fa-flask"></i> Request Access
        </button>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Sandbox request form modal-style -->
    <div id="sandbox-form-section" style="display:none;max-width:620px;margin:0 auto;" data-aos="fade-up">
      <div class="kw-card" style="padding:2rem;border-top:3px solid #22C55E;">
        <h3 style="margin-bottom:0.25rem;" id="sandbox-form-title">Request Sandbox Access</h3>
        <p style="font-size:0.8rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Credentials will be emailed to you within 2 hours during business hours.</p>
        <form id="sandbox-form" novalidate>
          <?= csrfField() ?>
          <input type="hidden" name="demo_type" value="sandbox">
          <input type="hidden" name="product_interest" id="sandbox-product-hidden">
          <input type="text" name="website" style="display:none;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
              <input type="text" name="name" class="kw-input" placeholder="Your name">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Work Email <span style="color:#EF4444;">*</span></label>
              <input type="email" name="email" class="kw-input" placeholder="you@company.com">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Company <span style="color:#EF4444;">*</span></label>
              <input type="text" name="company" class="kw-input" placeholder="Company name">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Phone</label>
              <input type="tel" name="phone" class="kw-input" placeholder="+254 700 000 000">
            </div>
            <div class="kw-form-group" style="grid-column:span 2;">
              <label class="kw-label">How will you use this sandbox?</label>
              <textarea name="message" class="kw-input" rows="3" placeholder="e.g. Evaluating for our 120-person HR team. Want to test payroll and leave management..."></textarea>
            </div>
          </div>
          <div id="sandbox-alert"></div>
          <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
            <button type="submit" class="kw-btn kw-btn-primary kw-btn-lg" id="sandbox-submit" style="background:#22C55E;border-color:#22C55E;">
              <i class="fa-solid fa-flask"></i> Request Sandbox Access
            </button>
            <button type="button" onclick="document.getElementById('sandbox-form-section').style.display='none'" class="kw-btn kw-btn-ghost">Cancel</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</section>

<script>
function requestSandbox(slug, name) {
  const section = document.getElementById('sandbox-form-section');
  document.getElementById('sandbox-product-hidden').value = name;
  document.getElementById('sandbox-form-title').textContent = 'Request Sandbox: ' + name;
  section.style.display = 'block';
  section.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.getElementById('sandbox-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('sandbox-submit');
  document.querySelectorAll('.kw-field-error').forEach(el => el.textContent = '');
  document.getElementById('sandbox-alert').innerHTML = '';
  btn.disabled = true;
  btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#fff;display:inline-block;margin-right:6px;"></div>Requesting...';
  try {
    const resp = await fetch('<?= url('api/demo-request') ?>', {
      method: 'POST',
      headers: { 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '' },
      body: new FormData(this)
    });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('sandbox-alert').innerHTML = `<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">✅ ${data.message} You'll receive sandbox credentials within 2 hours.</div>`;
      this.reset();
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k, v]) => {
        const el = this.querySelector(`[name="${k}"]`);
        if (el) { const err = el.closest('.kw-form-group')?.querySelector('.kw-field-error'); if (err) err.textContent = v; }
      });
      document.getElementById('sandbox-alert').innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err) {
    document.getElementById('sandbox-alert').innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error. Please try again.</div>';
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-flask"></i> Request Sandbox Access';
});
</script>
<style>@media(max-width:768px){.kw-container>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}.kw-card form>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}.kw-form-group[style*="grid-column:span 2"]{grid-column:span 1!important;}}</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>