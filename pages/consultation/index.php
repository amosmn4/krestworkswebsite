<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Book a Consultation — ' . APP_NAME;
$page_description = 'Book a digital transformation, system design, or AI implementation consultation with Krestworks Solutions experts.';
$page_keywords    = 'consultation, digital transformation, system design, AI implementation, software modernization, business automation, enterprise software consulting, technology strategy';

$consultationTypes = [
  ['digital-transformation','fa-arrow-trend-up','#F5A800','Digital Transformation','Roadmap to modernise your operations with enterprise software and AI.'],
  ['system-design','fa-drafting-compass','#3B82F6','System Architecture','Design scalable, secure systems from the ground up.'],
  ['ai-implementation','fa-robot','#A855F7','AI Implementation','Integrate AI copilots, automation, and predictive analytics into your workflow.'],
  ['software-modernization','fa-recycle','#22C55E','Software Modernization','Migrate legacy systems to modern, maintainable platforms.'],
  ['business-automation','fa-gears','#F97316','Business Automation','Identify and automate repetitive processes to save time and reduce error.'],
  ['general','fa-comments','#6B7280','General Consultation','Not sure where to start? Let\'s talk and we\'ll guide you.'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><span class="current">Consultation</span></div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-handshake"></i> Expert Guidance</span>
      <h1>Book a Consultation</h1>
      <p style="max-width:560px;color:rgba(255,255,255,0.65);">Work directly with our solutions engineers and business consultants. We understand your challenges and design the right technology path forward.</p>

      <!-- Consultation types grid -->
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.85rem;margin-top:2rem;">
        <?php foreach ($consultationTypes as $ct): ?>
        <div style="padding:1.25rem;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:var(--kw-radius-md);border-top:2px solid <?= $ct[3] ?>;cursor:pointer;transition:all 0.2s;"
             onclick="document.querySelector('[name=consultation_type][value=\'<?= $ct[0] ?>\']').checked=true;updateConsultType('<?= $ct[0] ?>');document.getElementById('consultation-form').scrollIntoView({behavior:'smooth'});"
             onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
          <i class="fa-solid <?= $ct[1] ?>" style="font-size:1rem;color:<?= $ct[3] ?>;margin-bottom:0.5rem;display:block;"></i>
          <div style="font-size:0.82rem;font-weight:700;color:#fff;"><?= $ct[4] ?></div>
          <div style="font-size:0.7rem;color:rgba(255,255,255,0.45);margin-top:0.2rem;"><?= $ct[5] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Why consult section -->
<section style="background:var(--kw-bg-alt);padding:3rem 0;border-bottom:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
      <div data-aos="fade-right">
        <span class="label"><i class="fa-solid fa-circle-question"></i> Why Consult First?</span>
        <h2>Build What You Need, Not What You Think You Need</h2>
        <p style="color:var(--kw-text-muted);line-height:1.75;margin-bottom:1.25rem;">Many organisations invest in technology and discover it doesn't solve their actual problem. A Krestworks consultation begins with understanding — your processes, pain points, team, and goals — before a single line of code is written.</p>
        <div style="display:flex;flex-direction:column;gap:0.65rem;">
          <?php foreach (['Free first consultation (30 minutes)','No obligation to proceed','Get a written recommendation','Senior-level consultants only','Africa-focused context and pricing'] as $b): ?>
          <div style="display:flex;align-items:center;gap:0.65rem;font-size:0.875rem;">
            <i class="fa-solid fa-check-circle" style="color:var(--kw-primary);flex-shrink:0;"></i> <?= $b ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div data-aos="fade-left">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
          <?php foreach ([
            ['200+','Consultations completed'],['95%','Client satisfaction'],['48h','Average time to proposal'],['12+','Industries served'],
          ] as $stat): ?>
          <div class="kw-card" style="padding:1.25rem;text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);"><?= $stat[0] ?></div>
            <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $stat[1] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Form -->
<section id="consultation-form" style="background:var(--kw-bg);padding:4rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 360px;gap:3rem;align-items:flex-start;">

      <div>
        <div style="margin-bottom:2rem;">
          <h2>Book Your Consultation</h2>
          <p style="color:var(--kw-text-muted);">All information is kept strictly confidential. We typically respond within 24 hours.</p>
        </div>

        <div class="kw-card" style="padding:2rem;">
          <form id="consult-form" novalidate>
            <?= csrfField() ?>
            <input type="text" name="website" style="display:none;">

            <!-- Consultation type -->
            <div style="margin-bottom:1.5rem;">
              <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.65rem;display:block;">
                Consultation Type <span style="color:#EF4444;">*</span>
              </label>
              <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;">
                <?php foreach ($consultationTypes as $i=>$ct): ?>
                <label style="cursor:pointer;">
                  <input type="radio" name="consultation_type" value="<?= $ct[0] ?>" <?= $i===0?'checked':'' ?> style="display:none;" class="ctype-radio">
                  <div class="ctype-opt" data-value="<?= $ct[0] ?>" data-color="<?= $ct[3] ?>"
                       style="padding:0.6rem 0.5rem;text-align:center;border:2px solid var(--kw-border);border-radius:var(--kw-radius-md);transition:all 0.2s;<?= $i===0?"border-color:{$ct[3]};background:{$ct[3]}10;":'' ?>">
                    <i class="fa-solid <?= $ct[1] ?>" style="font-size:0.85rem;color:<?= $ct[3] ?>;display:block;margin-bottom:0.2rem;"></i>
                    <span style="font-size:0.65rem;font-weight:600;line-height:1.3;display:block;"><?= $ct[4] ?></span>
                  </div>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
              <div class="kw-form-group">
                <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
                <input type="text" name="name" class="kw-input" placeholder="James Kamau">
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Email Address <span style="color:#EF4444;">*</span></label>
                <input type="email" name="email" class="kw-input" placeholder="james@company.co.ke">
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Phone Number <span style="color:#EF4444;">*</span></label>
                <input type="tel" name="phone" class="kw-input" placeholder="+254 700 000 000">
                <div class="kw-field-error"></div>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Company / Organisation</label>
                <input type="text" name="company" class="kw-input" placeholder="Your Company Name">
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Team Size</label>
                <select name="team_size" class="kw-input">
                  <option value="">-- Select --</option>
                  <option>Solo / Freelancer</option><option>2–10 employees</option><option>11–50 employees</option>
                  <option>51–200 employees</option><option>200+ employees</option>
                </select>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Budget Range</label>
                <select name="budget" class="kw-input">
                  <option value="">-- Prefer not to say --</option>
                  <option>Under KES 100,000</option><option>KES 100K – 500K</option>
                  <option>KES 500K – 2M</option><option>KES 2M+</option><option>To be discussed</option>
                </select>
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Preferred Date</label>
                <input type="date" name="preferred_date" class="kw-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
              </div>
              <div class="kw-form-group">
                <label class="kw-label">Preferred Time</label>
                <select name="preferred_time" class="kw-input">
                  <option value="">-- Flexible --</option>
                  <option>Morning (8am–12pm EAT)</option><option>Afternoon (2pm–5pm EAT)</option><option>Evening (5pm–7pm EAT)</option>
                </select>
              </div>
              <div class="kw-form-group" style="grid-column:span 2;">
                <label class="kw-label">Describe Your Challenge <span style="color:#EF4444;">*</span></label>
                <textarea name="message" class="kw-input" rows="5" placeholder="What problems are you trying to solve? What systems do you have? What does success look like for you?" required></textarea>
                <div class="kw-field-error"></div>
              </div>
            </div>

            <div id="consult-alert"></div>

            <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
              <button type="submit" class="kw-btn kw-btn-primary kw-btn-lg" id="consult-submit">
                <i class="fa-solid fa-handshake"></i> Book Free Consultation
              </button>
              <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="kw-btn" style="background:#25D366;color:#fff;border:none;font-size:0.875rem;padding:0.75rem 1.25rem;">
                <i class="fa-brands fa-whatsapp"></i> WhatsApp
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);display:flex;flex-direction:column;gap:1.25rem;">

        <div class="kw-card" style="padding:1.5rem;border-top:3px solid var(--kw-primary);">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Our Approach</h5>
          <?php foreach ([
            ['fa-search','1. Discovery','We listen first — understand your process and pain points fully.'],
            ['fa-pencil-ruler','2. Analysis','We map your operations and identify where technology adds real value.'],
            ['fa-file-alt','3. Proposal','A written, itemised recommendation within 48 hours.'],
            ['fa-rocket','4. Execution','If you proceed, we build, deploy, and support your system.'],
          ] as $step): ?>
          <div style="display:flex;gap:0.75rem;padding:0.65rem 0;border-bottom:1px solid var(--kw-border);">
            <i class="fa-solid <?= $step[0] ?>" style="color:var(--kw-primary);margin-top:0.15rem;font-size:0.85rem;flex-shrink:0;width:16px;"></i>
            <div>
              <div style="font-size:0.82rem;font-weight:700;"><?= $step[1] ?></div>
              <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $step[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Reach Us Directly</h5>
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

      </div>
    </div>
  </div>
</section>

<script>
document.querySelectorAll('.ctype-radio').forEach(r => {
  r.addEventListener('change', () => {
    document.querySelectorAll('.ctype-opt').forEach(opt => {
      const active = opt.dataset.value === r.value;
      opt.style.borderColor = active ? opt.dataset.color : '';
      opt.style.background  = active ? opt.dataset.color+'10' : '';
    });
  });
});
function updateConsultType(val) {
  document.querySelectorAll('.ctype-opt').forEach(opt => {
    const active = opt.dataset.value === val;
    opt.style.borderColor = active ? opt.dataset.color : '';
    opt.style.background  = active ? opt.dataset.color+'10' : '';
  });
}
document.getElementById('consult-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('consult-submit');
  document.querySelectorAll('.kw-field-error').forEach(el=>el.textContent='');
  document.getElementById('consult-alert').innerHTML='';
  btn.disabled=true;
  btn.innerHTML='<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Booking...';
  try {
    const resp = await fetch('<?= url('api/consultation') ?>', {
      method:'POST', headers:{'X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''}, body:new FormData(this)
    });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('consult-alert').innerHTML=`<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">${data.message}</div>`;
      this.reset(); window.Krest?.toast(data.message,'success');
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v])=>{const el=this.querySelector(`[name="${k}"]`);if(el){const err=el.closest('.kw-form-group')?.querySelector('.kw-field-error');if(err)err.textContent=v;}});
      document.getElementById('consult-alert').innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err){document.getElementById('consult-alert').innerHTML='<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error.</div>';}
  btn.disabled=false;
  btn.innerHTML='<i class="fa-solid fa-handshake"></i> Book Free Consultation';
});
</script>
<style>@media(max-width:1024px){.kw-container>div[style*="1fr 360px"]{grid-template-columns:1fr!important;}div[style*="position:sticky"]{position:static!important;}.kw-container>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}}</style>
<style>@media(max-width:768px){.kw-card form>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}.kw-form-group[style*="grid-column:span 2"]{grid-column:span 1!important;}}</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>