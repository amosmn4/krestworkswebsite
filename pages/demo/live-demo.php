<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Live Demo — ' . APP_NAME;
$page_description = 'Book a live, guided demo with a Krestworks solutions engineer. See any of our enterprise systems in action with real data and real-time Q&A.';


$products = [
  'HR Management System','Procurement Management System','eLearning Management System',
  'Real Estate Management System','Supply Chain Management System','Executive Decision Support',
  'CRM System','Hospital Management System','POS System','Multiple Products',
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('demo') ?>">Demo Center</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Live Demo</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label" style="background:rgba(245,168,0,0.15);color:#F5A800;"><i class="fa-solid fa-video"></i> Live Session</span>
      <h1>Live Guided Demo</h1>
      <p style="color:rgba(255,255,255,0.65);max-width:540px;">A 45-minute session with a Krestworks solutions engineer. We walk through the system tailored to your industry, answer questions in real time, and show you exactly how it solves your business challenges.</p>
      <div style="display:flex;gap:1.25rem;margin-top:1.5rem;flex-wrap:wrap;">
        <?php foreach ([['fa-clock','45 minutes'],['fa-video','Via Google Meet / Zoom'],['fa-user-tie','Senior engineer led'],['fa-comments','Live Q&A included']] as $d): ?>
        <div style="display:flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.08);border-radius:999px;padding:0.4rem 1rem;font-size:0.78rem;color:rgba(255,255,255,0.75);">
          <i class="fa-solid <?= $d[0] ?>" style="color:var(--kw-primary);font-size:0.7rem;"></i> <?= $d[1] ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- What to expect -->
<section style="background:var(--kw-bg-alt);padding:3rem 0;border-bottom:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;" data-aos="fade-up">
      <div>
        <h2 style="margin-bottom:1rem;">What Happens in the Demo?</h2>
        <div style="display:flex;flex-direction:column;gap:0;">
          <?php foreach ([
            ['1','#F5A800','Pre-Demo Brief (5 min)','We confirm your role, team size, and specific pain points you want to see addressed.'],
            ['2','#3B82F6','Product Walkthrough (25 min)','Core modules, key workflows, and features most relevant to your industry.'],
            ['3','#22C55E','Live Customisation (10 min)','We configure the demo environment to reflect your business — your branding, your data structure.'],
            ['4','#A855F7','Q&A (5 min)','Open floor — ask anything about features, integrations, pricing, or implementation.'],
          ] as $step): ?>
          <div style="display:flex;gap:1rem;padding:1.1rem 0;border-bottom:1px solid var(--kw-border);">
            <div style="width:32px;height:32px;border-radius:50%;background:<?= $step[1] ?>;color:#0A0F1A;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;flex-shrink:0;"><?= $step[0] ?></div>
            <div>
              <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.2rem;"><?= $step[2] ?></div>
              <div style="font-size:0.8rem;color:var(--kw-text-muted);"><?= $step[3] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
          <?php foreach ([
            ['fa-users','#F5A800','Who Attends','You and up to 5 colleagues. We recommend including your operations lead.'],
            ['fa-laptop','#3B82F6','What You Need','A computer with stable internet. We share screen and send a link 30 min before.'],
            ['fa-shield-halved','#22C55E','NDA Available','We can sign an NDA before the demo if you want to share sensitive data.'],
            ['fa-gift','#A855F7','After the Demo','You receive a full proposal, feature comparison, and implementation timeline.'],
          ] as $card): ?>
          <div class="kw-card" style="padding:1.25rem;">
            <i class="fa-solid <?= $card[0] ?>" style="font-size:1.1rem;color:<?= $card[1] ?>;margin-bottom:0.65rem;display:block;"></i>
            <div style="font-size:0.82rem;font-weight:700;margin-bottom:0.25rem;"><?= $card[2] ?></div>
            <p style="font-size:0.75rem;color:var(--kw-text-muted);margin:0;"><?= $card[3] ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Booking form -->
<section style="background:var(--kw-bg);padding:4rem 0;">
  <div class="kw-container" style="max-width:760px;">
    <div style="text-align:center;margin-bottom:2rem;">
      <h2>Book Your Live Demo</h2>
      <p style="color:var(--kw-text-muted);">We confirm within 24 hours and send a calendar invite with the meeting link.</p>
    </div>
    <div class="kw-card" style="padding:2rem;">
      <form id="live-demo-form" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="demo_type" value="live">
        <input type="text" name="website" style="display:none;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
          <div class="kw-form-group">
            <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
            <input type="text" name="name" class="kw-input" placeholder="Jane Muthoni">
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Work Email <span style="color:#EF4444;">*</span></label>
            <input type="email" name="email" class="kw-input" placeholder="jane@company.co.ke">
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Phone Number</label>
            <input type="tel" name="phone" class="kw-input" placeholder="+254 700 000 000">
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Company <span style="color:#EF4444;">*</span></label>
            <input type="text" name="company" class="kw-input" placeholder="Company Name">
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Product to Demo <span style="color:#EF4444;">*</span></label>
            <select name="product_interest" class="kw-input">
              <option value="">-- Select --</option>
              <?php foreach ($products as $p): ?><option><?= $p ?></option><?php endforeach; ?>
            </select>
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Number of Attendees</label>
            <select name="attendees" class="kw-input">
              <option>1 person (just me)</option>
              <option>2–3 people</option>
              <option>4–5 people</option>
              <option>6+ people (enterprise)</option>
            </select>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Preferred Date</label>
            <input type="date" name="preferred_date" class="kw-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Preferred Time (EAT)</label>
            <select name="preferred_time" class="kw-input">
              <option value="">-- Flexible --</option>
              <option>8:00 AM – 10:00 AM</option>
              <option>10:00 AM – 12:00 PM</option>
              <option>2:00 PM – 4:00 PM</option>
              <option>4:00 PM – 6:00 PM</option>
            </select>
          </div>
          <div class="kw-form-group" style="grid-column:span 2;">
            <label class="kw-label">What specific challenges should we focus on?</label>
            <textarea name="message" class="kw-input" rows="3" placeholder="e.g. We manually process payroll for 300 staff every month. I want to see how the system handles bulk payroll runs and exceptions..."></textarea>
          </div>
        </div>
        <div id="live-demo-alert"></div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
          <button type="submit" class="kw-btn kw-btn-primary kw-btn-lg" id="live-submit">
            <i class="fa-solid fa-calendar-check"></i> Book Live Demo
          </button>
          <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="kw-btn kw-btn-lg" style="background:#25D366;color:#fff;border:none;">
            <i class="fa-brands fa-whatsapp"></i> Book via WhatsApp
          </a>
        </div>
      </form>
    </div>
    <div style="text-align:center;margin-top:1.5rem;font-size:0.8rem;color:var(--kw-text-muted);">
      Prefer another demo type? 
      <a href="<?= url('demo/recorded') ?>" style="color:var(--kw-primary);">Watch a recording</a> or 
      <a href="<?= url('demo/sandbox') ?>" style="color:var(--kw-primary);">access sandbox</a>
    </div>
  </div>
</section>

<script>
document.getElementById('live-demo-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('live-submit');
  document.querySelectorAll('.kw-field-error').forEach(el => el.textContent = '');
  document.getElementById('live-demo-alert').innerHTML = '';
  btn.disabled = true;
  btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Booking...';
  try {
    const resp = await fetch('<?= url('api/demo-request') ?>', {
      method: 'POST',
      headers: { 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '' },
      body: new FormData(this)
    });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('live-demo-alert').innerHTML = `<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">${data.message}</div>`;
      this.reset();
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k, v]) => {
        const el = this.querySelector(`[name="${k}"]`);
        if (el) { const err = el.closest('.kw-form-group')?.querySelector('.kw-field-error'); if (err) err.textContent = v; }
      });
      document.getElementById('live-demo-alert').innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err) {
    document.getElementById('live-demo-alert').innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error. Please try again.</div>';
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-calendar-check"></i> Book Live Demo';
});
</script>
<style>@media(max-width:1024px){.kw-container>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}}
@media(max-width:640px){.kw-card form>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}.kw-form-group[style*="grid-column:span 2"]{grid-column:span 1!important;}}</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>