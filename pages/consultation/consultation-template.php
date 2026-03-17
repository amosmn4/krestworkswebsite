<?php
/**
 * Shared consultation detail template.
 * Expects $consult array with all consultation data.
 */
if (!isset($consult)) die('Direct access not allowed.');
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('consultation') ?>">Consultation</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current"><?= e($consult['title']) ?></span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label" style="background:<?= $consult['color'] ?>20;color:<?= $consult['color'] ?>;"><i class="fa-solid <?= $consult['icon'] ?>"></i> Consultation</span>
      <h1><?= e($consult['title']) ?></h1>
      <p style="color:rgba(255,255,255,0.65);max-width:580px;"><?= e($consult['tagline']) ?></p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:4rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 360px;gap:3rem;align-items:flex-start;">

      <!-- Main content -->
      <div>
        <!-- Overview -->
        <div style="margin-bottom:2.5rem;">
          <h2 style="margin-bottom:1rem;"><?= e($consult['title']) ?></h2>
          <p style="font-size:0.95rem;color:var(--kw-text-secondary);line-height:1.8;margin-bottom:1.5rem;"><?= e($consult['description']) ?></p>

          <?php if (!empty($consult['challenges'])): ?>
          <div class="kw-card" style="padding:1.5rem;margin-bottom:1.5rem;background:<?= $consult['color'] ?>08;border-color:<?= $consult['color'] ?>30;">
            <h4 style="color:<?= $consult['color'] ?>;margin-bottom:1rem;font-size:0.875rem;text-transform:uppercase;letter-spacing:0.06em;">
              <i class="fa-solid fa-triangle-exclamation"></i> Challenges This Solves
            </h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
              <?php foreach ($consult['challenges'] as $ch): ?>
              <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;color:var(--kw-text-secondary);">
                <i class="fa-solid fa-arrow-right" style="color:<?= $consult['color'] ?>;font-size:0.65rem;flex-shrink:0;"></i><?= e($ch) ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($consult['outcomes'])): ?>
          <h3 style="margin-bottom:1rem;">What You Get</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:2rem;">
            <?php foreach ($consult['outcomes'] as $out): ?>
            <div class="kw-card" style="padding:1rem;display:flex;align-items:flex-start;gap:0.65rem;">
              <i class="fa-solid fa-check-circle" style="color:<?= $consult['color'] ?>;flex-shrink:0;margin-top:0.1rem;"></i>
              <span style="font-size:0.82rem;"><?= e($out) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Process -->
        <?php if (!empty($consult['process'])): ?>
        <div style="margin-bottom:2.5rem;">
          <h3 style="margin-bottom:1.25rem;">How It Works</h3>
          <div style="display:flex;flex-direction:column;gap:0;">
            <?php foreach ($consult['process'] as $i => $step): ?>
            <div style="display:flex;gap:1rem;padding:1.1rem 0;border-bottom:1px solid var(--kw-border);">
              <div style="width:32px;height:32px;border-radius:50%;background:<?= $consult['color'] ?>;color:#0A0F1A;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;flex-shrink:0;"><?= $i + 1 ?></div>
              <div>
                <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.2rem;"><?= e($step[0]) ?></div>
                <div style="font-size:0.8rem;color:var(--kw-text-muted);"><?= e($step[1]) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Who is this for -->
        <?php if (!empty($consult['whoIsItFor'])): ?>
        <div style="margin-bottom:2.5rem;">
          <h3 style="margin-bottom:1rem;">Who Is This For?</h3>
          <div style="display:flex;flex-wrap:wrap;gap:0.6rem;">
            <?php foreach ($consult['whoIsItFor'] as $who): ?>
            <span style="background:<?= $consult['color'] ?>10;color:<?= $consult['color'] ?>;border:1px solid <?= $consult['color'] ?>30;border-radius:999px;padding:0.35rem 0.85rem;font-size:0.78rem;font-weight:600;"><?= e($who) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- FAQ -->
        <?php if (!empty($consult['faqs'])): ?>
        <div>
          <h3 style="margin-bottom:1.25rem;">Common Questions</h3>
          <div class="kw-accordion-group">
            <?php foreach ($consult['faqs'] as $faq): ?>
            <div class="kw-accordion-item">
              <button class="kw-accordion-trigger"><?= e($faq[0]) ?><i class="fa-solid fa-chevron-down"></i></button>
              <div class="kw-accordion-body"><div class="kw-accordion-body-inner"><?= e($faq[1]) ?></div></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar booking -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);">
        <div class="kw-card" style="padding:2rem;border-top:3px solid <?= $consult['color'] ?>;">
          <h4 style="margin-bottom:0.3rem;">Book This Consultation</h4>
          <p style="font-size:0.8rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Free first session, 30 minutes. Our expert will review your situation before the call.</p>

          <?php foreach ([
            ['fa-clock','Duration','30–60 minutes'],
            ['fa-calendar','Availability','Mon–Fri, 8am–6pm EAT'],
            ['fa-video','Format','Video call or in-person (Nairobi)'],
            ['fa-file-alt','After Session','Written recommendations sent within 48h'],
          ] as $info): ?>
          <div style="display:flex;align-items:center;gap:0.65rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.8rem;">
            <i class="fa-solid <?= $info[0] ?>" style="color:<?= $consult['color'] ?>;width:14px;flex-shrink:0;"></i>
            <span style="color:var(--kw-text-muted);flex:1;"><?= $info[1] ?></span>
            <span style="font-weight:600;"><?= $info[2] ?></span>
          </div>
          <?php endforeach; ?>

          <div style="margin-top:1.25rem;display:flex;flex-direction:column;gap:0.6rem;">
            <a href="<?= url('consultation') ?>?type=<?= $consult['slug'] ?>" class="kw-btn kw-btn-primary" style="justify-content:center;background:<?= $consult['color'] ?>;border-color:<?= $consult['color'] ?>;<?= $consult['color']==='#F5A800'?'color:#0A0F1A;':'' ?>">
              <i class="fa-solid fa-calendar-check"></i> Book Free Session
            </a>
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi%2C+I%27m+interested+in+<?= urlencode($consult['title']) ?>" target="_blank" class="kw-btn" style="justify-content:center;background:#25D366;color:#fff;border:none;">
              <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
            </a>
            <a href="mailto:<?= COMPANY_EMAIL ?>?subject=<?= urlencode($consult['title'] . ' — ' . APP_NAME) ?>" class="kw-btn kw-btn-ghost" style="justify-content:center;">
              <i class="fa-solid fa-envelope"></i> Email Us
            </a>
          </div>
        </div>

        <!-- Related consultations -->
        <?php if (!empty($consult['related'])): ?>
        <div class="kw-card" style="padding:1.25rem;margin-top:1rem;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Related Consultations</h5>
          <?php foreach ($consult['related'] as $rel): ?>
          <a href="<?= url('consultation/' . $rel[0]) ?>" style="display:flex;align-items:center;gap:0.65rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;">
            <i class="fa-solid <?= $rel[1] ?>" style="color:<?= $consult['color'] ?>;width:14px;"></i>
            <?= e($rel[2]) ?>
            <i class="fa-solid fa-arrow-right" style="margin-left:auto;font-size:0.65rem;color:var(--kw-text-muted);"></i>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</section>

<!-- CTA band -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);padding:3rem 0;">
  <div class="kw-container" style="text-align:center;">
    <h3 style="margin-bottom:0.5rem;">Not sure if this is the right consultation?</h3>
    <p style="color:var(--kw-text-muted);margin-bottom:1.5rem;">Book a General Consultation — our team will assess your situation and guide you to the right path.</p>
    <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-handshake"></i> All Consultation Types</a>
      <a href="<?= url('services') ?>" class="kw-btn kw-btn-ghost kw-btn-lg"><i class="fa-solid fa-cogs"></i> View Our Services</a>
    </div>
  </div>
</section>

<style>
@media(max-width:1024px){ .kw-container>div[style*="1fr 360px"]{grid-template-columns:1fr!important;} div[style*="position:sticky"]{position:static!important;} }
@media(max-width:640px){ div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;} }
</style>