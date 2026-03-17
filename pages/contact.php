<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'Contact Us — ' . APP_NAME;
$page_description = 'Get in touch with Krestworks Solutions — email, phone, WhatsApp, or visit our office in Nairobi, Kenya.';

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><span class="current">Contact</span></div>
    <div style="text-align:center;padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-envelope"></i> Get In Touch</span>
      <h1>We'd Love to Hear from You</h1>
      <p style="color:rgba(255,255,255,0.6);max-width:480px;margin:0 auto;">Have a question, project in mind, or just want to explore what Krestworks can do for your organisation? Reach out — we respond within 24 hours.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">

    <!-- Contact channels -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:3rem;" data-aos="fade-up">
      <?php foreach ([
        ['fa-envelope','#F5A800','Email Us',COMPANY_EMAIL,'mailto:'.COMPANY_EMAIL,'Response within 24 hours'],
        ['fa-phone','#3B82F6','Call Us',COMPANY_PHONE,'tel:'.COMPANY_PHONE,'Mon–Fri, 8am–6pm EAT'],
        ['fa-brands fa-whatsapp','#25D366','WhatsApp',WHATSAPP_DISPLAY,'https://wa.me/'.WHATSAPP_NUMBER,'Instant messaging'],
        ['fa-location-dot','#A855F7','Visit Us',COMPANY_ADDRESS,'#map','By appointment'],
      ] as $ch): ?>
      <a href="<?= $ch[4] ?>" <?= $ch[4]==='#map'?'onclick="document.getElementById(\'map\').scrollIntoView({behavior:\'smooth\'});return false;"':'' ?> target="<?= str_starts_with($ch[4],'http')?'_blank':'_self' ?>"
         class="kw-card" style="padding:1.5rem;text-align:center;text-decoration:none;transition:all 0.2s;border-top:3px solid <?= $ch[1] ?>;"
         onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
        <div style="width:48px;height:48px;border-radius:12px;background:<?= $ch[1] ?>15;display:flex;align-items:center;justify-content:center;margin:0 auto 0.85rem;">
          <i class="<?= str_contains($ch[0],'brands')?$ch[0]:'fa-solid '.$ch[0] ?>" style="font-size:1.1rem;color:<?= $ch[1] ?>;"></i>
        </div>
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.3rem;"><?= $ch[2] ?></div>
        <div style="font-size:0.82rem;font-weight:600;color:var(--kw-text-primary);margin-bottom:0.2rem;"><?= $ch[3] ?></div>
        <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $ch[5] ?></div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Form + sidebar -->
    <div style="display:grid;grid-template-columns:1fr 340px;gap:3rem;align-items:flex-start;" data-aos="fade-up">

      <div class="kw-card" style="padding:2rem;">
        <h3 style="margin-bottom:1.5rem;">Send Us a Message</h3>
        <form id="contact-form" novalidate>
          <?= csrfField() ?>
          <input type="text" name="website" style="display:none;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
              <input type="text" name="name" class="kw-input" placeholder="Your full name">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Email Address <span style="color:#EF4444;">*</span></label>
              <input type="email" name="email" class="kw-input" placeholder="you@company.com">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Phone Number</label>
              <input type="tel" name="phone" class="kw-input" placeholder="+254 700 000 000">
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Company</label>
              <input type="text" name="company" class="kw-input" placeholder="Your company name">
            </div>
            <div class="kw-form-group" style="grid-column:span 2;">
              <label class="kw-label">Subject <span style="color:#EF4444;">*</span></label>
              <select name="subject" class="kw-input">
                <option value="">-- Select a subject --</option>
                <option>Product Inquiry</option><option>Request a Demo</option><option>Custom Development Quote</option>
                <option>Technical Support</option><option>Partnership Opportunity</option><option>Billing Question</option>
                <option>General Inquiry</option>
              </select>
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group" style="grid-column:span 2;">
              <label class="kw-label">Message <span style="color:#EF4444;">*</span></label>
              <textarea name="message" class="kw-input" rows="6" placeholder="Tell us how we can help..."></textarea>
              <div class="kw-field-error"></div>
            </div>
          </div>

          <div id="contact-alert"></div>

          <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <button type="submit" class="kw-btn kw-btn-primary kw-btn-lg" id="contact-submit">
              <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
            <span style="font-size:0.75rem;color:var(--kw-text-muted);">We respond within 24 hours</span>
          </div>
        </form>
      </div>

      <!-- Info sidebar -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);display:flex;flex-direction:column;gap:1.25rem;">

        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Office Hours</h5>
          <?php foreach ([['Monday – Friday','8:00 AM – 6:00 PM EAT'],['Saturday','9:00 AM – 1:00 PM EAT'],['Sunday','Closed (Emergency only)']] as $h): ?>
          <div style="display:flex;justify-content:space-between;font-size:0.8rem;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);">
            <span style="color:var(--kw-text-muted);"><?= $h[0] ?></span>
            <span style="font-weight:600;"><?= $h[1] ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Follow Us</h5>
          <?php foreach ([
            ['fa-brands fa-linkedin','#0A66C2','LinkedIn',SOCIAL_LINKEDIN,'Follow for updates'],
            ['fa-brands fa-twitter','#1D9BF0','Twitter / X',SOCIAL_TWITTER,'News and insights'],
            ['fa-brands fa-github','#333','GitHub',SOCIAL_GITHUB,'Open source projects'],
            ['fa-brands fa-youtube','#FF0000','YouTube',SOCIAL_YOUTUBE,'Tutorials and demos'],
          ] as $social): ?>
          <?php if ($social[3]): ?>
          <a href="<?= $social[3] ?>" target="_blank" style="display:flex;align-items:center;gap:0.65rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);text-decoration:none;">
            <i class="<?= $social[0] ?>" style="color:<?= $social[1] ?>;width:16px;font-size:0.9rem;"></i>
            <div>
              <div style="font-size:0.8rem;font-weight:600;color:var(--kw-text-primary);"><?= $social[2] ?></div>
              <div style="font-size:0.68rem;color:var(--kw-text-muted);"><?= $social[4] ?></div>
            </div>
          </a>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.5rem;background:var(--kw-primary)10;border-color:var(--kw-primary)30;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-primary);margin-bottom:0.5rem;">Prefer WhatsApp?</h5>
          <p style="font-size:0.8rem;color:var(--kw-text-secondary);margin-bottom:0.85rem;">Get a faster response by messaging us directly on WhatsApp.</p>
          <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi%20Krestworks%2C%20I%20have%20an%20inquiry." target="_blank" class="kw-btn" style="background:#25D366;color:#fff;border:none;width:100%;justify-content:center;">
            <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
          </a>
        </div>
      </div>
    </div>

    <!-- Map -->
    <div id="map" style="margin-top:3rem;" data-aos="fade-up">
      <h3 style="margin-bottom:1rem;">Find Us</h3>
      <div class="kw-card" style="padding:0;overflow:hidden;">
        <div style="padding:1.25rem;background:var(--kw-bg-alt);border-bottom:1px solid var(--kw-border);display:flex;align-items:center;gap:0.75rem;">
          <i class="fa-solid fa-location-dot" style="color:var(--kw-primary);font-size:1.1rem;"></i>
          <div>
            <div style="font-size:0.85rem;font-weight:700;"><?= APP_NAME ?></div>
            <div style="font-size:0.78rem;color:var(--kw-text-muted);"><?= COMPANY_ADDRESS ?></div>
          </div>
        </div>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d255282.35853743566!2d36.6823855!3d-1.3028617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1172d84d49a7%3A0xf7cf0254b297924c!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2ske!4v1234567890"
          width="100%" height="350" style="border:0;display:block;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>
</section>

<script>
document.getElementById('contact-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('contact-submit');
  document.querySelectorAll('.kw-field-error').forEach(el=>el.textContent='');
  document.getElementById('contact-alert').innerHTML='';
  btn.disabled=true;
  btn.innerHTML='<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Sending...';
  try {
    const resp = await fetch('<?= url('api/contact') ?>', {
      method:'POST', headers:{'X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''}, body:new FormData(this)
    });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('contact-alert').innerHTML=`<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">${data.message}</div>`;
      this.reset(); window.Krest?.toast(data.message,'success');
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v])=>{const el=this.querySelector(`[name="${k}"]`);if(el){const err=el.closest('.kw-form-group')?.querySelector('.kw-field-error');if(err)err.textContent=v;}});
      document.getElementById('contact-alert').innerHTML=`<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err){document.getElementById('contact-alert').innerHTML='<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error. Please try again.</div>';}
  btn.disabled=false;
  btn.innerHTML='<i class="fa-solid fa-paper-plane"></i> Send Message';
});
</script>
<style>
@media(max-width:1024px){.kw-container>div[style*="repeat(4,1fr)"]{grid-template-columns:repeat(2,1fr)!important;}.kw-container>div[style*="1fr 340px"]{grid-template-columns:1fr!important;}div[style*="position:sticky"]{position:static!important;}}
@media(max-width:640px){.kw-container>div[style*="repeat(4,1fr)"]{grid-template-columns:1fr 1fr!important;}.kw-card form>div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}.kw-form-group[style*="grid-column:span 2"]{grid-column:span 1!important;}}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>