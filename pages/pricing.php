<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'Pricing — ' . APP_NAME;
$page_description = 'Transparent pricing for Krestworks enterprise software. Subscription plans, enterprise licenses, and custom packages with deployment, training, and support.';


$plans = [
  ['Starter','starter','#3B82F6',false,'For small businesses & startups','KES 15,000','KES 12,000',
   ['1 enterprise system','Up to 25 users','Cloud hosting included','Email support','Basic analytics','Monthly backups','Standard onboarding'],
   ['Multi-system access','Custom integrations','Dedicated account manager','On-premise deployment']],
  ['Professional','professional','#F5A800',true,'For growing organisations','KES 35,000','KES 28,000',
   ['3 enterprise systems','Up to 100 users','Cloud hosting included','Priority support (24h)','Advanced analytics','Daily backups','Full onboarding + training','API access','Custom branding'],
   ['Custom integrations','Dedicated account manager','On-premise deployment']],
  ['Enterprise','enterprise','#22C55E',false,'For large organisations','Custom','Custom',
   ['Unlimited systems','Unlimited users','Cloud or on-premise','Dedicated support engineer','Full analytics suite','Real-time backups','Bespoke onboarding','Full API & integration support','Custom development','Dedicated account manager','SLA guarantee'],
   []],
];

$features = [
  'Enterprise Systems Access' => ['1 system','3 systems','All systems'],
  'User Accounts' => ['Up to 25','Up to 100','Unlimited'],
  'Cloud Hosting' => [true,true,true],
  'On-Premise Option' => [false,false,true],
  'API Access' => [false,true,true],
  'Custom Integrations' => [false,false,true],
  'Analytics & Reporting' => ['Basic','Advanced','Full Suite'],
  'Data Backups' => ['Monthly','Daily','Real-time'],
  'Email Support' => [true,true,true],
  'Priority Support' => [false,true,true],
  'Dedicated Account Manager' => [false,false,true],
  'User Training' => ['Standard','Full','Bespoke'],
  'Custom Development' => [false,false,true],
  'SLA Guarantee' => [false,false,true],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><span class="current">Pricing</span></div>
    <div style="text-align:center;padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-tag"></i> Transparent Pricing</span>
      <h1>Choose Your Plan</h1>
      <p style="color:rgba(255,255,255,0.6);max-width:500px;margin:0 auto 1.5rem;">No hidden fees. No surprises. All plans include setup, onboarding, and ongoing technical support.</p>

      <!-- Billing toggle -->
      <div style="display:inline-flex;align-items:center;gap:0.75rem;background:rgba(255,255,255,0.08);border-radius:999px;padding:0.35rem 0.85rem;">
        <span id="toggle-monthly" style="font-size:0.82rem;font-weight:700;color:#fff;">Monthly</span>
        <div id="billing-toggle" onclick="toggleBilling()" style="width:42px;height:24px;background:var(--kw-primary);border-radius:999px;cursor:pointer;position:relative;transition:background 0.2s;">
          <div id="toggle-dot" style="width:18px;height:18px;background:#0A0F1A;border-radius:50%;position:absolute;top:3px;left:3px;transition:transform 0.2s;"></div>
        </div>
        <span id="toggle-annual" style="font-size:0.82rem;font-weight:400;color:rgba(255,255,255,0.5);">
          Annual <span style="background:var(--kw-primary);color:#0A0F1A;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.65rem;font-weight:800;margin-left:0.2rem;">Save 20%</span>
        </span>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">

    <!-- Pricing cards -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:4rem;" data-aos="fade-up">
      <?php foreach ($plans as $plan): ?>
      <div class="kw-card" style="padding:0;overflow:hidden;<?= $plan[3]?"border-color:{$plan[2]};box-shadow:0 0 0 2px {$plan[2]}30;":'' ?>position:relative;">
        <?php if ($plan[3]): ?>
        <div style="background:<?= $plan[2] ?>;color:#0A0F1A;text-align:center;padding:0.35rem;font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;">
          ★ Most Popular
        </div>
        <?php endif; ?>
        <div style="padding:2rem;">
          <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:<?= $plan[2] ?>20;display:flex;align-items:center;justify-content:center;">
              <i class="fa-solid <?= $plan[1]==='starter'?'fa-seedling':($plan[1]==='professional'?'fa-rocket':'fa-building') ?>" style="color:<?= $plan[2] ?>;font-size:1rem;"></i>
            </div>
            <div>
              <div style="font-weight:800;font-size:1rem;font-family:var(--font-heading);"><?= $plan[0] ?></div>
              <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $plan[4] ?></div>
            </div>
          </div>

          <?php if ($plan[5]==='Custom'): ?>
          <div style="font-size:1.6rem;font-weight:800;font-family:var(--font-heading);color:<?= $plan[2] ?>;margin-bottom:0.25rem;">Custom</div>
          <div style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Tailored to your scale and needs</div>
          <?php else: ?>
          <div style="margin-bottom:1.5rem;">
            <div class="price-monthly">
              <span style="font-size:1.6rem;font-weight:800;font-family:var(--font-heading);color:<?= $plan[2] ?>;"><?= $plan[5] ?></span>
              <span style="font-size:0.78rem;color:var(--kw-text-muted);"> / month</span>
            </div>
            <div class="price-annual" style="display:none;">
              <span style="font-size:1.6rem;font-weight:800;font-family:var(--font-heading);color:<?= $plan[2] ?>;"><?= $plan[6] ?></span>
              <span style="font-size:0.78rem;color:var(--kw-text-muted);"> / month, billed annually</span>
            </div>
          </div>
          <?php endif; ?>

          <?php if ($plan[5]==='Custom'): ?>
          <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-lg" style="width:100%;justify-content:center;margin-bottom:1.5rem;background:<?= $plan[2] ?>;color:#fff;border:none;">
            <i class="fa-solid fa-phone"></i> Contact Sales
          </a>
          <?php elseif ($plan[3]): ?>
          <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-lg" style="width:100%;justify-content:center;margin-bottom:1.5rem;background:<?= $plan[2] ?>;color:#0A0F1A;border:none;">
            <i class="fa-solid fa-arrow-right"></i> Get Started
          </a>
          <?php else: ?>
          <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-ghost kw-btn-lg" style="width:100%;justify-content:center;margin-bottom:1.5rem;border-color:<?= $plan[2] ?>50;color:<?= $plan[2] ?>;">
            <i class="fa-solid fa-arrow-right"></i> Get Started
          </a>
          <?php endif; ?>

          <div style="display:flex;flex-direction:column;gap:0.5rem;">
            <?php foreach ($plan[7] as $feat): ?>
            <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;">
              <i class="fa-solid fa-check" style="color:<?= $plan[2] ?>;font-size:0.7rem;flex-shrink:0;"></i>
              <?= $feat ?>
            </div>
            <?php endforeach; ?>
            <?php foreach ($plan[8] as $feat): ?>
            <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;opacity:0.4;">
              <i class="fa-solid fa-times" style="font-size:0.7rem;flex-shrink:0;"></i>
              <?= $feat ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Feature comparison table -->
    <div data-aos="fade-up">
      <h2 style="text-align:center;margin-bottom:0.5rem;">Compare Plans</h2>
      <p style="text-align:center;color:var(--kw-text-muted);margin-bottom:2rem;font-size:0.875rem;">Full feature comparison across all three tiers</p>

      <div class="kw-card" style="padding:0;overflow:hidden;overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:600px;">
          <thead>
            <tr style="background:var(--kw-bg-alt);">
              <th style="padding:1rem 1.25rem;text-align:left;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);width:40%;">Feature</th>
              <?php foreach ($plans as $plan): ?>
              <th style="padding:1rem;text-align:center;font-size:0.85rem;font-weight:700;color:<?= $plan[2] ?>;"><?= $plan[0] ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($features as $feature => $vals): ?>
            <tr style="border-top:1px solid var(--kw-border);">
              <td style="padding:0.85rem 1.25rem;font-size:0.82rem;color:var(--kw-text-secondary);"><?= $feature ?></td>
              <?php foreach ($vals as $i => $val): ?>
              <td style="padding:0.85rem;text-align:center;font-size:0.82rem;">
                <?php if ($val===true): ?>
                  <i class="fa-solid fa-check-circle" style="color:#22C55E;"></i>
                <?php elseif ($val===false): ?>
                  <i class="fa-solid fa-times-circle" style="color:var(--kw-text-muted);opacity:0.3;"></i>
                <?php else: ?>
                  <span style="color:var(--kw-text-secondary);"><?= $val ?></span>
                <?php endif; ?>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Custom Package section -->
    <div class="kw-card" style="padding:2.5rem;margin-top:3rem;border-top:3px solid var(--kw-primary);display:grid;grid-template-columns:1fr auto;gap:2rem;align-items:center;" data-aos="fade-up">
      <div>
        <span class="label" style="margin-bottom:0.65rem;"><i class="fa-solid fa-cubes"></i> Custom Package</span>
        <h3 style="margin-bottom:0.5rem;">Need Something Specific?</h3>
        <p style="color:var(--kw-text-muted);font-size:0.875rem;max-width:500px;">We build custom enterprise software packages that combine development, integration, deployment, training, and long-term maintenance. Contact us for a tailored quote.</p>
        <div style="display:flex;gap:1.5rem;margin-top:1rem;flex-wrap:wrap;">
          <?php foreach (['Custom Development','System Integration','Deployment & Hosting','User Training','Ongoing Maintenance'] as $item): ?>
          <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:var(--kw-text-secondary);">
            <i class="fa-solid fa-check" style="color:var(--kw-primary);font-size:0.65rem;"></i> <?= $item ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:0.65rem;flex-shrink:0;">
        <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-lg">
          <i class="fa-solid fa-handshake"></i> Get Custom Quote
        </a>
        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="kw-btn" style="background:#25D366;color:#fff;border:none;justify-content:center;">
          <i class="fa-brands fa-whatsapp"></i> WhatsApp Us
        </a>
      </div>
    </div>

    <!-- FAQ -->
    <div style="margin-top:4rem;" data-aos="fade-up">
      <h2 style="text-align:center;margin-bottom:2rem;">Pricing FAQ</h2>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;max-width:860px;margin:0 auto;">
        <?php foreach ([
          ['Do prices include VAT?','All prices are exclusive of VAT. VAT at 16% applies for Kenyan clients. We provide proper tax invoices.'],
          ['Can I switch plans?','Yes, upgrade or downgrade at any time. Changes take effect at the next billing cycle.'],
          ['Is there a free trial?','We offer a 14-day trial for Starter and Professional plans. No credit card required.'],
          ['What payment methods do you accept?','M-Pesa, bank transfer, credit card (Visa/Mastercard), and cheque for enterprise clients.'],
          ['What does "user" mean?','A user is one person with their own login account. Shared/service accounts are not counted.'],
          ['Do you offer NGO / startup discounts?','Yes — contact us. We offer special pricing for registered NGOs and pre-revenue startups.'],
        ] as $faq): ?>
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.875rem;margin-bottom:0.4rem;"><i class="fa-solid fa-circle-question" style="color:var(--kw-primary);margin-right:0.35rem;"></i><?= $faq[0] ?></h5>
          <p style="font-size:0.8rem;color:var(--kw-text-muted);line-height:1.6;"><?= $faq[1] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<script>
let isAnnual = false;
function toggleBilling() {
  isAnnual = !isAnnual;
  document.getElementById('toggle-dot').style.transform = isAnnual ? 'translateX(18px)' : '';
  document.getElementById('toggle-monthly').style.fontWeight = isAnnual ? '400' : '700';
  document.getElementById('toggle-monthly').style.color = isAnnual ? 'rgba(255,255,255,0.5)' : '#fff';
  document.getElementById('toggle-annual').style.fontWeight = isAnnual ? '700' : '400';
  document.getElementById('toggle-annual').style.color = isAnnual ? '#fff' : 'rgba(255,255,255,0.5)';
  document.querySelectorAll('.price-monthly').forEach(el => el.style.display = isAnnual?'none':'');
  document.querySelectorAll('.price-annual').forEach(el => el.style.display = isAnnual?'':'none');
}
</script>
<style>
@media(max-width:1024px){ .kw-container>div[style*="repeat(3,1fr)"]{grid-template-columns:1fr!important;} .kw-container>div[style*="1fr auto"]{grid-template-columns:1fr!important;} }
@media(max-width:768px){ .kw-container>div[style*="1fr 1fr"]:last-of-type{grid-template-columns:1fr!important;} }
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>