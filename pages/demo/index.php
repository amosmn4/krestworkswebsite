<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Demo Center — ' . APP_NAME;
$page_description = 'Request a live demo, watch recorded walkthroughs, or get sandbox access to Krestworks enterprise systems. Book your demo today.';


$products = [
  'HR Management System','Procurement Management System','eLearning Management System',
  'Real Estate Management System','Supply Chain Management System','Executive Decision Support',
  'CRM System','Hospital Management System','POS System',
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><span class="current">Demo Center</span></div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-play-circle"></i> Experience Krestworks</span>
      <h1>See Our Systems in Action</h1>
      <p style="max-width:560px;color:rgba(255,255,255,0.65);">Choose how you want to experience Krestworks — live guided demo, self-paced recording, or hands-on sandbox access. No commitment required.</p>

      <!-- Demo type cards -->
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:2rem;">
        <?php foreach ([
          ['fa-video','Live Demo','Guided walkthrough with a Krestworks solutions engineer. Ask questions in real-time.','45 min','#F5A800','live','live-demo'],
          ['fa-film','Recorded Demo','Watch a full product walkthrough at your own pace, any time.','20–30 min','#3B82F6','recorded','recorded-demo'],
          ['fa-flask','Sandbox Access','Hands-on access to a test environment. Explore features yourself.','7-day access','#22C55E','sandbox','sandbox'],
        ] as $dt): ?>
        <a href="<?= url("demo/{$dt[6]}") ?>" style="text-decoration:none;">
          <div class="demo-type-card" style="padding:1.5rem;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:var(--kw-radius-lg);border-top:3px solid <?= $dt[4] ?>;cursor:pointer;transition:all 0.2s;"
           onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
        <i class="fa-solid <?= $dt[0] ?>" style="font-size:1.5rem;color:<?= $dt[4] ?>;margin-bottom:0.75rem;display:block;"></i>
        <h3 style="color:#fff;font-size:1rem;margin-bottom:0.3rem;"><?= $dt[1] ?></h3>
        <p style="color:rgba(255,255,255,0.5);font-size:0.78rem;margin-bottom:0.5rem;"><?= $dt[2] ?></p>
        <span style="background:<?= $dt[4] ?>25;color:<?= $dt[4] ?>;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.68rem;font-weight:700;"><?= $dt[3] ?></span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      </div>
    </div>
  </div>
</section>

<!-- Knowledge Hub section -->
<section style="background:var(--kw-bg-alt);padding:3rem 0;border-bottom:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="text-align:center;margin-bottom:2rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-graduation-cap"></i> Knowledge Hub</span>
      <h2>Before Your Demo</h2>
      <p style="color:var(--kw-text-muted);">Explore real-world cases, security scenarios, and best practices to ask better questions.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.25rem;" data-aos="fade-up">
      <?php foreach ([
        ['fa-check-circle','#22C55E','Best Case: HR System','How Krestworks HR reduced payroll processing from 3 days to 4 hours for a 500-person organization.'],
        ['fa-exclamation-triangle','#EF4444','Worst Case: No System','A real estate firm lost KES 12M tracking rent manually. What happens without a proper system.'],
        ['fa-shield-halved','#A855F7','Security in Enterprise Software','How Krestworks protects sensitive HR, financial, and patient data.'],
        ['fa-chart-line','#3B82F6','ROI in 90 Days','A procurement system case: 40% reduction in approval time, 25% cost savings in Year 1.'],
        ['fa-cloud','#F5A800','Cloud vs On-Premise','When to choose cloud-hosted vs on-premise deployment. A detailed comparison.'],
        ['fa-users','#F97316','User Adoption Strategy','The #1 reason ERP implementations fail — and how to avoid it.'],
      ] as $kh): ?>
      <div class="kw-card" style="padding:1.25rem;cursor:pointer;transition:all 0.2s;border-top:3px solid <?= $kh[2] ?>;"
           onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
        <i class="fa-solid <?= $kh[0] ?>" style="font-size:1.2rem;color:<?= $kh[2] ?>;margin-bottom:0.65rem;display:block;"></i>
        <h4 style="font-size:0.875rem;margin-bottom:0.4rem;"><?= $kh[3] ?></h4>
        <p style="font-size:0.78rem;color:var(--kw-text-muted);line-height:1.5;"><?= $kh[4] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Booking Form -->
<section id="book-form" style="background:var(--kw-bg);padding:4rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 380px;gap:3rem;align-items:flex-start;">

      <!-- Form -->
      <div>
        <div data-aos="fade-up" style="margin-bottom:2rem;">
          <span class="label"><i class="fa-solid fa-calendar-check"></i> Book a Demo</span>
          <h2>Schedule Your Demo</h2>
          <p style="color:var(--kw-text-muted);">Fill in the form and our team will confirm within 24 hours.</p>
        </div>

        <div class="kw-card" style="padding:2rem;">
          <form id="demo-form" novalidate>
            <?= csrfField() ?>
            <input type="text" name="website" style="display:none;">

            <!-- Demo type selector -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;margin-bottom:1.5rem;">
              <?php foreach (['live'=>['fa-video','Live Demo'],'recorded'=>['fa-film','Recorded'],'sandbox'=>['fa-flask','Sandbox']] as $val=>$d): ?>
              <label style="cursor:pointer;">
                <input type="radio" name="demo_type" id="demo-type" value="<?= $val ?>" <?= $val==='live'?'checked':'' ?> style="display:none;" class="demo-radio">
                <div class="demo-type-opt" data-value="<?= $val ?>" style="padding:0.6rem;text-align:center;border:2px solid var(--kw-border);border-radius:var(--kw-radius-md);transition:all 0.2s;<?= $val==='live'?'border-color:var(--kw-primary);background:var(--kw-primary)10;':'' ?>">
                  <i class="fa-solid <?= $d[0] ?>" style="font-size:0.9rem;color:var(--kw-primary);display:block;margin-bottom:0.2rem;"></i>
                  <span style="font-size:0.72rem;font-weight:600;"><?= $d[1] ?></span>
                </div>
              </label>
              <?php endforeach; ?>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
              <div class="kw-form-group">
                <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
                <input type="text" name="name" class="kw-input" placeholder="Jane Muthoni">
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Email Address <span style="color:#EF4444;">*</span></label>
                <input type="email" name="email" class="kw-input" placeholder="jane@company.com">
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Phone Number</label>
                <input type="tel" name="phone" class="kw-input" placeholder="+254 700 000 000">
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Company <span style="color:#EF4444;">*</span></label>
                <input type="text" name="company" class="kw-input" placeholder="Your Company">
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Product of Interest <span style="color:#EF4444;">*</span></label>
                <select name="product_interest" class="kw-input">
                  <option value="">-- Select a product --</option>
                  <?php foreach ($products as $p): ?><option><?= $p ?></option><?php endforeach; ?>
                  <option>Multiple Products</option>
                </select>
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Preferred Date</label>
                <input type="date" name="preferred_date" class="kw-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
              </div>
              <div class="kw-form-group" style="grid-column:span 2;">
                <label class="kw-label">Preferred Time</label>
                <select name="preferred_time" class="kw-input">
                  <option value="">-- Flexible --</option>
                  <option>8:00 AM – 10:00 AM EAT</option><option>10:00 AM – 12:00 PM EAT</option>
                  <option>2:00 PM – 4:00 PM EAT</option><option>4:00 PM – 6:00 PM EAT</option>
                </select>
              </div>
              <div class="kw-form-group" style="grid-column:span 2;">
                <label class="kw-label">Additional Notes</label>
                <textarea name="message" class="kw-input" rows="3" placeholder="Specific features you want to see? Current challenges? Team size?"></textarea>
              </div>
            </div>

            <div id="demo-form-alert"></div>

            <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
              <button type="submit" class="kw-btn kw-btn-primary kw-btn-lg" id="demo-submit">
                <i class="fa-solid fa-calendar-check"></i> Book My Demo
              </button>
              <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="kw-btn kw-btn-ghost kw-btn-lg" style="color:#25D366;border-color:#25D36630;">
                <i class="fa-brands fa-whatsapp"></i> WhatsApp Us
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);display:flex;flex-direction:column;gap:1.25rem;">

        <div class="kw-card" style="padding:1.5rem;border-top:3px solid var(--kw-primary);">
          <h5 style="margin-bottom:1rem;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">What to Expect</h5>
          <?php foreach ([
            ['1','fa-envelope','Confirmation Email','Within 1 hour of submission'],
            ['2','fa-phone','Briefing Call','Our team calls to understand your needs'],
            ['3','fa-calendar','Scheduled Demo','Tailored to your specific use case'],
            ['4','fa-file-alt','Follow-up Proposal','Custom proposal sent after the demo'],
          ] as $step): ?>
          <div style="display:flex;gap:0.75rem;padding:0.65rem 0;border-bottom:1px solid var(--kw-border);">
            <div style="width:24px;height:24px;border-radius:50%;background:var(--kw-primary);color:#0A0F1A;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.65rem;flex-shrink:0;"><?= $step[0] ?></div>
            <div>
              <div style="font-size:0.82rem;font-weight:700;"><?= $step[2] ?></div>
              <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $step[3] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="margin-bottom:1rem;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Contact Directly</h5>
          <a href="mailto:<?= COMPANY_EMAIL ?>" style="display:flex;align-items:center;gap:0.65rem;font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);">
            <i class="fa-solid fa-envelope" style="color:var(--kw-primary);width:16px;"></i> <?= COMPANY_EMAIL ?>
          </a>
          <a href="tel:<?= COMPANY_PHONE ?>" style="display:flex;align-items:center;gap:0.65rem;font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);">
            <i class="fa-solid fa-phone" style="color:var(--kw-primary);width:16px;"></i> <?= COMPANY_PHONE ?>
          </a>
          <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" style="display:flex;align-items:center;gap:0.65rem;font-size:0.82rem;color:#25D366;text-decoration:none;padding:0.5rem 0;">
            <i class="fa-brands fa-whatsapp" style="width:16px;"></i> <?= WHATSAPP_DISPLAY ?>
          </a>
        </div>

        <div class="kw-card" style="padding:1.5rem;background:var(--kw-primary)10;border-color:var(--kw-primary)30;">
          <div style="font-size:0.78rem;font-weight:700;color:var(--kw-primary);margin-bottom:0.5rem;">⚡ Fast Response</div>
          <p style="font-size:0.8rem;color:var(--kw-text-secondary);">We confirm all demo requests within <strong>24 hours</strong>. Weekday demos available 8am–6pm EAT.</p>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
// Demo type radio visual update
document.querySelectorAll('.demo-radio').forEach(radio => {
  radio.addEventListener('change', () => {
    document.querySelectorAll('.demo-type-opt').forEach(opt => {
      const active = opt.dataset.value === radio.value;
      opt.style.borderColor = active ? 'var(--kw-primary)' : '';
      opt.style.background  = active ? 'var(--kw-primary)10' : '';
    });
  });
});

// Form submit
document.getElementById('demo-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('demo-submit');
  const alertDiv = document.getElementById('demo-form-alert');

  // Clear errors
  document.querySelectorAll('.kw-field-error').forEach(el => el.textContent='');
  alertDiv.innerHTML = '';

  btn.disabled=true;
  btn.innerHTML='<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Booking...';

  const fd = new FormData(this);
  try {
    const resp = await fetch('<?= url('api/demo-request') ?>', {
      method:'POST', headers:{'X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''}, body:fd
    });
    const data = await resp.json();
    if (data.success) {
      alertDiv.innerHTML = `<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">${data.message}</div>`;
      this.reset();
      window.Krest?.toast(data.message,'success');
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v])=>{const el=this.querySelector(`[name="${k}"]`); if(el){const err=el.closest('.kw-form-group')?.querySelector('.kw-field-error');if(err)err.textContent=v;}});
      alertDiv.innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err) { alertDiv.innerHTML='<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error. Please try again.</div>'; }
  btn.disabled=false;
  btn.innerHTML='<i class="fa-solid fa-calendar-check"></i> Book My Demo';
});
</script>
<style>@media(max-width:1024px){.kw-container>div[style*="1fr 380px"]{grid-template-columns:1fr!important;}div[style*="position:sticky"]{position:static!important;}}</style>
<style>@media(max-width:768px){.kw-card form>div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}.kw-card form .kw-form-group[style*="grid-column:span 2"]{grid-column:span 1!important;}}</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>