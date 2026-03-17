<?php
// Shared product detail template
// Requires: $product (array), $screenshots, $features_detailed, $faqs, $tech_stack
if (!isset($product)) die();
?>

<!-- Hero -->
<section class="kw-page-hero" style="padding-bottom:0;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('products') ?>">Products</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current"><?= e($product['name']) ?></span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;padding:3rem 0;" data-aos="fade-up">
      <div>
        <div style="display:inline-flex;align-items:center;gap:0.65rem;background:<?= $product['color'] ?>18;border:1px solid <?= $product['color'] ?>35;padding:0.4rem 1rem;border-radius:999px;margin-bottom:1.25rem;">
          <i class="fa-solid <?= $product['icon'] ?>" style="color:<?= $product['color'] ?>;"></i>
          <span style="font-size:0.75rem;font-weight:700;color:<?= $product['color'] ?>;text-transform:uppercase;letter-spacing:0.08em;">Enterprise System</span>
        </div>
        <h1 style="color:#fff;margin-bottom:0.85rem;"><?= e($product['name']) ?></h1>
        <p style="color:rgba(255,255,255,0.65);font-size:1.05rem;margin-bottom:2rem;"><?= e($product['tagline']) ?></p>
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:2rem;">
          <?php foreach ($product['deployment'] as $dep): ?>
            <span style="background:<?= $product['color'] ?>18;color:<?= $product['color'] ?>;border:1px solid <?= $product['color'] ?>35;border-radius:999px;padding:0.28rem 0.8rem;font-size:0.75rem;font-weight:600;"><?= $dep ?></span>
          <?php endforeach; ?>
          <?php foreach ($product['industries'] as $ind): ?>
            <span style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.6);border:1px solid rgba(255,255,255,0.12);border-radius:999px;padding:0.28rem 0.8rem;font-size:0.75rem;"><?= $ind ?></span>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;gap:0.85rem;flex-wrap:wrap;">
          <a href="<?= url('demo') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-play-circle"></i> Request Demo</a>
          <a href="<?= url('pricing') ?>" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.2);"><i class="fa-solid fa-tags"></i> View Pricing</a>
        </div>
      </div>
      <!-- Hero visual -->
      <div>
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:var(--kw-radius-xl);padding:2rem;position:relative;overflow:hidden;">
          <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:<?= $product['color'] ?>08;pointer-events:none;"></div>
          <!-- Mini dashboard preview -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.75rem;">
            <?php foreach (array_slice($product['stats'], 0, 4) as $stat): ?>
            <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:var(--kw-radius-md);padding:1rem;text-align:center;">
              <div style="font-size:1.4rem;font-weight:800;color:<?= $product['color'] ?>;font-family:var(--font-heading);"><?= $stat[0] ?></div>
              <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);margin-top:0.15rem;"><?= $stat[1] ?></div>
            </div>
            <?php endforeach; ?>
          </div>
          <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:var(--kw-radius-md);padding:0.85rem;">
            <canvas id="product-preview-chart" height="120"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:flex-start;">

      <!-- Left: Tabs -->
      <div>
        <div class="kw-tabs" data-tabs-container id="product-tabs">
          <button class="kw-tab-btn active" data-tab="features">Features</button>
          <button class="kw-tab-btn" data-tab="modules">Modules</button>
          <button class="kw-tab-btn" data-tab="tech">Tech Stack</button>
          <button class="kw-tab-btn" data-tab="faq">FAQ</button>
        </div>

        <!-- Features -->
        <div class="kw-tab-panel active" data-tab-panel="features">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <?php foreach ($features_detailed as $feat): ?>
            <div class="kw-card" style="padding:1.25rem;border-left:3px solid <?= $product['color'] ?>;">
              <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                <i class="fa-solid <?= $feat[0] ?>" style="color:<?= $product['color'] ?>;font-size:1rem;margin-top:0.15rem;flex-shrink:0;"></i>
                <div>
                  <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.2rem;"><?= e($feat[1]) ?></div>
                  <div style="font-size:0.8rem;color:var(--kw-text-muted);"><?= e($feat[2]) ?></div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Modules -->
        <div class="kw-tab-panel" data-tab-panel="modules">
          <p style="margin-bottom:1.5rem;color:var(--kw-text-muted);">The system is composed of independent, activatable modules. Deploy the full suite or start with what you need.</p>
          <div style="display:flex;flex-direction:column;gap:0.5rem;">
            <?php foreach ($product['modules'] as $i => $mod): ?>
            <div class="kw-accordion-item">
              <button class="kw-accordion-trigger">
                <span style="display:flex;align-items:center;gap:0.65rem;">
                  <span style="background:<?= $product['color'] ?>15;color:<?= $product['color'] ?>;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;flex-shrink:0;"><?= $i+1 ?></span>
                  <?= e($mod[0]) ?>
                </span>
                <i class="fa-solid fa-chevron-down"></i>
              </button>
              <div class="kw-accordion-body">
                <div class="kw-accordion-body-inner">
                  <p><?= e($mod[1]) ?></p>
                  <?php if (!empty($mod[2])): ?>
                  <div style="display:flex;flex-wrap:wrap;gap:0.3rem;margin-top:0.75rem;">
                    <?php foreach ($mod[2] as $subfeature): ?>
                      <span style="background:<?= $product['color'] ?>0F;color:<?= $product['color'] ?>;border:1px solid <?= $product['color'] ?>25;border-radius:999px;padding:0.2rem 0.65rem;font-size:0.72rem;font-weight:600;"><?= $subfeature ?></span>
                    <?php endforeach; ?>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Tech Stack -->
        <div class="kw-tab-panel" data-tab-panel="tech">
          <h4 style="margin-bottom:1rem;">Technologies & Architecture</h4>
          <?php foreach ($tech_stack as $layer): ?>
          <div style="margin-bottom:1.5rem;">
            <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:<?= $product['color'] ?>;margin-bottom:0.6rem;"><?= e($layer[0]) ?></div>
            <div class="tech-pills-wrap">
              <?php foreach ($layer[1] as $t): ?>
                <span class="tech-pill"><?= e($t) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>

          <!-- Architecture diagram (text-based) -->
          <div style="background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.5rem;margin-top:1.5rem;">
            <h5 style="margin-bottom:1rem;font-size:0.85rem;color:var(--kw-text-muted);">System Architecture</h5>
            <div style="display:flex;flex-direction:column;gap:0.5rem;font-size:0.8rem;">
              <?php
              $layers = [
                ['Presentation Layer','React / HTML5 / Tailwind — responsive UI for web and mobile'],
                ['API Layer','RESTful PHP API — authenticated, rate-limited, versioned'],
                ['Business Logic','Service classes, workflow engine, event dispatcher'],
                ['Data Layer','MySQL with PDO — encrypted sensitive fields, indexed queries'],
                ['AI Layer','Anthropic API integration for intelligent features'],
                ['Infrastructure','Cloud / cPanel / Docker — monitored with uptime alerts'],
              ];
              foreach ($layers as $l): ?>
              <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0.85rem;border-radius:var(--kw-radius-sm);background:var(--kw-bg-alt);border:1px solid var(--kw-border);">
                <i class="fa-solid fa-layer-group" style="color:<?= $product['color'] ?>;font-size:0.75rem;flex-shrink:0;"></i>
                <span style="font-weight:600;min-width:160px;color:var(--kw-text-secondary);"><?= $l[0] ?></span>
                <span style="color:var(--kw-text-muted);"><?= $l[1] ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- FAQ -->
        <div class="kw-tab-panel" data-tab-panel="faq">
          <div class="kw-accordion-group">
            <?php foreach ($faqs as $faq): ?>
            <div class="kw-accordion-item">
              <button class="kw-accordion-trigger"><?= e($faq[0]) ?><i class="fa-solid fa-chevron-down"></i></button>
              <div class="kw-accordion-body"><div class="kw-accordion-body-inner"><?= e($faq[1]) ?></div></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Right: Sidebar -->
      <div>
        <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:calc(var(--kw-nav-height)+1.5rem);">

          <!-- Pricing CTA -->
          <div class="kw-card" style="padding:1.5rem;border-top:3px solid <?= $product['color'] ?>;">
            <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.3rem;">Starting From</div>
            <div style="font-size:2rem;font-weight:800;color:<?= $product['color'] ?>;font-family:var(--font-heading);margin-bottom:0.25rem;"><?= e($product['price_from']) ?></div>
            <div style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Per deployment · Custom licensing available</div>
            <a href="<?= url('demo') ?>" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;margin-bottom:0.6rem;background:<?= $product['color'] ?>;border-color:<?= $product['color'] ?>;"><i class="fa-solid fa-play-circle"></i> Request Demo</a>
            <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-ghost" style="width:100%;justify-content:center;"><i class="fa-solid fa-calendar-check"></i> Free Consultation</a>
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="kw-btn" style="width:100%;justify-content:center;background:#25D366;color:#fff;border:none;margin-top:0.6rem;" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
          </div>

          <!-- Key Details -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">System Details</h5>
            <?php
            $details = [
              ['fa-clock',      'Delivery',    $product['delivery']  ?? '8–16 weeks'],
              ['fa-server',     'Deployment',  implode(', ', $product['deployment'])],
              ['fa-users',      'User Types',  $product['user_types'] ?? 'Admin, Staff, Viewer'],
              ['fa-mobile-alt', 'Mobile',      $product['mobile']    ?? 'Responsive Web + App'],
              ['fa-language',   'Languages',   $product['languages'] ?? 'English, Swahili'],
              ['fa-headset',    'Support',     '24/7 SLA-based'],
            ];
            foreach ($details as $d): ?>
            <div style="display:flex;justify-content:space-between;gap:0.5rem;padding:0.55rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;">
              <span style="color:var(--kw-text-muted);display:flex;align-items:center;gap:0.35rem;flex-shrink:0;"><i class="fa-solid <?= $d[0] ?>" style="color:<?= $product['color'] ?>;width:14px;"></i><?= $d[1] ?></span>
              <span style="font-weight:600;text-align:right;"><?= e($d[2]) ?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Related Products -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Related Products</h5>
            <?php foreach ($product['related'] as $r): ?>
            <a href="<?= url('products/'.$r[0]) ?>" style="display:flex;align-items:center;gap:0.65rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.85rem;color:var(--kw-text-secondary);text-decoration:none;">
              <i class="fa-solid <?= $r[1] ?>" style="color:var(--kw-primary);width:16px;"></i><?= e($r[2]) ?>
              <i class="fa-solid fa-arrow-right" style="margin-left:auto;font-size:0.7rem;color:var(--kw-text-muted);"></i>
            </a>
            <?php endforeach; ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);padding:4rem 0;">
  <div class="kw-container" style="text-align:center;" data-aos="fade-up">
    <h2 style="margin-bottom:0.75rem;">Ready to deploy <?= e($product['name']) ?>?</h2>
    <p style="color:var(--kw-text-muted);max-width:500px;margin:0 auto 2rem;">Request a live demo or start a consultation. Our team will tailor the system to your exact requirements.</p>
    <div style="display:flex;gap:0.85rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('demo') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-play-circle"></i> Request Live Demo</a>
      <a href="<?= url('products') ?>" class="kw-btn kw-btn-ghost kw-btn-lg"><i class="fa-solid fa-arrow-left"></i> All Products</a>
    </div>
  </div>
</section>

<style>
@media(max-width:768px){
  .kw-container > div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}
  .kw-container > div[style*="grid-template-columns:2fr 1fr"]{grid-template-columns:1fr!important;}
}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
  const ctx = document.getElementById('product-preview-chart');
  if(!ctx || typeof Chart==='undefined') return;
  new Chart(ctx,{
    type:'line',
    data:{
      labels:['Jan','Feb','Mar','Apr','May','Jun'],
      datasets:[{
        label:'System Activity',
        data:<?= json_encode($product['chart_data'] ?? [65,72,68,85,91,88]) ?>,
        borderColor:'<?= $product['color'] ?>',
        backgroundColor:'<?= $product['color'] ?>18',
        borderWidth:2,fill:true,tension:0.4,
        pointBackgroundColor:'<?= $product['color'] ?>',pointRadius:3
      }]
    },
    options:{
      responsive:true,plugins:{legend:{display:false},tooltip:{backgroundColor:'#111827',titleColor:'<?= $product['color'] ?>',bodyColor:'#D1D5DB'}},
      scales:{
        x:{ticks:{color:'rgba(255,255,255,0.3)',font:{size:9}},grid:{color:'rgba(255,255,255,0.04)'}},
        y:{ticks:{color:'rgba(255,255,255,0.3)',font:{size:9}},grid:{color:'rgba(255,255,255,0.05)'}}
      }
    }
  });
});
</script>