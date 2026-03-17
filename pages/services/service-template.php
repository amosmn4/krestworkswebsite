<?php
// Shared service detail template
// Variables expected: $service (array with all service data)
if (!isset($service)) die('Direct access not allowed.');
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('services') ?>">Services</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current"><?= e($service['title']) ?></span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid <?= $service['icon'] ?>"></i> Service</span>
      <h1><?= e($service['title']) ?></h1>
      <p><?= e($service['tagline']) ?></p>
    </div>
  </div>
</section>

<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:flex-start;" data-aos="fade-up">

      <div>
        <!-- Tabs -->
        <div class="kw-tabs" data-tabs-container>
          <button class="kw-tab-btn active" data-tab="overview">Overview</button>
          <button class="kw-tab-btn" data-tab="process">Process</button>
          <button class="kw-tab-btn" data-tab="faq">FAQ</button>
        </div>

        <!-- Overview -->
        <div class="kw-tab-panel active" data-tab-panel="overview">
          <p style="font-size:1rem;margin-bottom:2rem;"><?= e($service['desc']) ?></p>
          <?php if (!empty($service['whatWeBuild'])): ?>
          <h4 style="margin-bottom:1rem;">What We Build</h4>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:2rem;">
            <?php foreach ($service['whatWeBuild'] as $item): ?>
            <div class="kw-card" style="padding:1.1rem;">
              <div style="display:flex;align-items:flex-start;gap:0.65rem;">
                <i class="fa-solid <?= $item[0] ?>" style="color:<?= $service['color'] ?>;font-size:0.95rem;margin-top:0.15rem;flex-shrink:0;"></i>
                <div>
                  <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.15rem;"><?= e($item[1]) ?></div>
                  <div style="font-size:0.78rem;color:var(--kw-text-muted);"><?= e($item[2]) ?></div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <?php if (!empty($service['tech'])): ?>
          <h4 style="margin-bottom:0.85rem;">Technologies</h4>
          <div class="tech-pills-wrap">
            <?php foreach ($service['tech'] as $t): ?>
              <span class="tech-pill"><?= e($t) ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Process -->
        <div class="kw-tab-panel" data-tab-panel="process">
          <h3 style="margin-bottom:1.5rem;">Delivery Process</h3>
          <?php if (!empty($service['process'])): ?>
          <div class="kw-steps">
            <?php foreach ($service['process'] as $i => $step): ?>
            <div class="kw-step">
              <div class="kw-step-num" style="background:<?= $service['color'] ?>15;border-color:<?= $service['color'] ?>;color:<?= $service['color'] ?>;"><?= $i+1 ?></div>
              <div class="kw-step-body"><h4><?= e($step) ?></h4></div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- FAQ -->
        <div class="kw-tab-panel" data-tab-panel="faq">
          <h3 style="margin-bottom:1.5rem;">Frequently Asked Questions</h3>
          <?php if (!empty($service['faqs'])): ?>
          <div class="kw-accordion-group">
            <?php foreach ($service['faqs'] as $faq): ?>
            <div class="kw-accordion-item">
              <button class="kw-accordion-trigger"><?= e($faq[0]) ?><i class="fa-solid fa-chevron-down"></i></button>
              <div class="kw-accordion-body"><div class="kw-accordion-body-inner"><?= e($faq[1]) ?></div></div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Sidebar -->
      <div>
        <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:calc(var(--kw-nav-height) + 1.5rem);">
          <div class="kw-card kw-card-gold-top" style="padding:1.5rem;">
            <h4 style="margin-bottom:0.5rem;">Get Started</h4>
            <p style="font-size:0.85rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Ready to discuss your project? Book a free consultation.</p>
            <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;margin-bottom:0.6rem;"><i class="fa-solid fa-calendar-check"></i> Book Consultation</a>
            <a href="<?= url('demo') ?>" class="kw-btn kw-btn-ghost" style="width:100%;justify-content:center;"><i class="fa-solid fa-play-circle"></i> Request Demo</a>
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="kw-btn" style="width:100%;justify-content:center;background:#25D366;color:#fff;border:none;margin-top:0.6rem;" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp Us</a>
          </div>
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="margin-bottom:1rem;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Service Info</h5>
            <?php
            $infoItems = [
              ['fa-clock','Timeline', $service['timeline'] ?? 'Custom'],
              ['fa-tags','Pricing',   $service['pricing']  ?? 'Custom Quote'],
              ['fa-layer-group','Method','Agile / Scrum'],
              ['fa-headset','Support','SLA-based'],
            ];
            foreach ($infoItems as $item): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.55rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;">
              <span style="color:var(--kw-text-muted);display:flex;align-items:center;gap:0.4rem;"><i class="fa-solid <?= $item[0] ?>" style="color:var(--kw-primary);width:14px;"></i><?= $item[1] ?></span>
              <span style="font-weight:600;"><?= e($item[2]) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php if (!empty($service['related'])): ?>
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="margin-bottom:1rem;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Related Services</h5>
            <?php foreach ($service['related'] as $r): ?>
            <a href="<?= url('services/'.$r[0]) ?>" style="display:flex;align-items:center;gap:0.65rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.85rem;color:var(--kw-text-secondary);text-decoration:none;transition:color 0.2s;">
              <i class="fa-solid <?= $r[1] ?>" style="color:var(--kw-primary);width:16px;"></i><?= e($r[2]) ?>
              <i class="fa-solid fa-arrow-right" style="margin-left:auto;font-size:0.7rem;color:var(--kw-text-muted);"></i>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);padding:4rem 0;">
  <div class="kw-container" style="text-align:center;">
    <h2 style="margin-bottom:0.75rem;">Ready to Get Started?</h2>
    <p style="color:var(--kw-text-muted);max-width:480px;margin:0 auto 2rem;">Let's discuss your <?= strtolower(e($service['title'])) ?> requirements. Free consultation, no commitment.</p>
    <div style="display:flex;gap:0.85rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-calendar-check"></i> Book Free Consultation</a>
      <a href="<?= url('services') ?>" class="kw-btn kw-btn-ghost kw-btn-lg"><i class="fa-solid fa-arrow-left"></i> All Services</a>
    </div>
  </div>
</section>