<?php
$portal_title  = 'Downloads';
$portal_active = 'downloads';
require_once __DIR__ . '/portal-header.php';

$pdo = db();

// Get user's active subscriptions to determine what they can download
$subs = $pdo->prepare("SELECT p.slug, p.name FROM subscriptions s JOIN products p ON p.id=s.product_id WHERE s.user_id=? AND s.status='active'");
$subs->execute([$userId]);
$activeSlugs = $subs->fetchAll(PDO::FETCH_ASSOC);
$activeSlugList = array_column($activeSlugs, 'slug');

// All downloadable resources (static for now; DB-driven in production)
$resources = [
  [
    'category'  => 'User Manuals',
    'icon'      => 'fa-book',
    'color'     => '#3B82F6',
    'items'     => [
      ['HR System — User Guide v2.1','PDF','4.2 MB','hr-system','2025-02-15'],
      ['Procurement System — User Guide v1.8','PDF','3.8 MB','procurement-system','2025-01-20'],
      ['eLearning System — User Guide v1.5','PDF','2.9 MB','elearning-system','2025-01-10'],
      ['Real Estate System — User Guide v1.3','PDF','3.1 MB','real-estate-system','2024-12-18'],
      ['Supply Chain — User Guide v1.2','PDF','2.7 MB','supply-chain-system','2024-12-01'],
      ['CRM System — User Guide v1.6','PDF','3.4 MB','crm-system','2025-02-10'],
      ['Hospital System — User Guide v1.4','PDF','5.1 MB','hospital-system','2025-01-28'],
    ],
  ],
  [
    'category'  => 'Technical Documentation',
    'icon'      => 'fa-file-code',
    'color'     => '#A855F7',
    'items'     => [
      ['HR System — API Reference v2.0','PDF','1.8 MB','hr-system','2025-02-01'],
      ['Procurement — Integration Guide','PDF','2.2 MB','procurement-system','2025-01-15'],
      ['Database Schema Reference','PDF','0.9 MB',null,'2025-01-05'],
      ['System Security Guide (All Products)','PDF','1.5 MB',null,'2025-01-30'],
    ],
  ],
  [
    'category'  => 'Quick Reference Guides',
    'icon'      => 'fa-bolt',
    'color'     => '#F5A800',
    'items'     => [
      ['HR System — Admin Quick Reference','PDF','0.8 MB','hr-system','2025-02-20'],
      ['Payroll Processing Cheat Sheet','PDF','0.5 MB','hr-system','2025-02-01'],
      ['Procurement Approval Workflow Chart','PDF','0.6 MB','procurement-system','2025-01-22'],
      ['eLearning — Course Creation Guide','PDF','0.7 MB','elearning-system','2025-01-15'],
    ],
  ],
  [
    'category'  => 'Templates & Resources',
    'icon'      => 'fa-file-alt',
    'color'     => '#22C55E',
    'items'     => [
      ['HR — Employee Onboarding Checklist','XLSX','0.2 MB',null,'2025-01-10'],
      ['Procurement — Supplier Evaluation Template','XLSX','0.3 MB',null,'2024-12-20'],
      ['Monthly Reports Template Pack','XLSX','0.4 MB',null,'2024-12-15'],
      ['IT Implementation Project Plan','XLSX','0.5 MB',null,'2025-02-01'],
    ],
  ],
];
?>

<div class="portal-page-header">
  <div>
    <h1>Downloads</h1>
    <p>Access user manuals, technical docs, templates, and resources for your subscribed systems.</p>
  </div>
</div>

<?php if (empty($activeSlugList)): ?>
<div class="portal-card" style="text-align:center;padding:3rem 2rem;">
  <i class="fa-solid fa-lock" style="font-size:2.5rem;color:var(--kw-text-muted);opacity:0.35;margin-bottom:1rem;display:block;"></i>
  <h3 style="margin-bottom:0.5rem;">No Active Subscriptions</h3>
  <p style="color:var(--kw-text-muted);font-size:0.875rem;margin-bottom:1.5rem;max-width:400px;margin-left:auto;margin-right:auto;">Subscribe to a Krestworks system to unlock system-specific documentation and resources.</p>
  <a href="<?= url('products') ?>" target="_blank" class="kw-btn kw-btn-primary"><i class="fa-solid fa-arrow-right"></i> Browse Products</a>
</div>
<?php else: ?>

<!-- Active systems banner -->
<div style="display:flex;gap:0.65rem;flex-wrap:wrap;margin-bottom:1.5rem;">
  <span style="font-size:0.72rem;font-weight:700;color:var(--kw-text-muted);align-self:center;">YOUR SYSTEMS:</span>
  <?php foreach ($activeSlugs as $s): ?>
  <span style="background:rgba(245,168,0,0.1);border:1px solid rgba(245,168,0,0.3);color:var(--kw-primary);border-radius:999px;padding:0.2rem 0.7rem;font-size:0.72rem;font-weight:700;">
    <i class="fa-solid fa-check" style="margin-right:0.25rem;font-size:0.6rem;"></i><?= e($s['name']) ?>
  </span>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Download categories -->
<?php foreach ($resources as $cat): ?>
<div class="portal-card" style="margin-bottom:1.25rem;">
  <div class="portal-card-header">
    <span class="portal-card-title">
      <i class="fa-solid <?= $cat['icon'] ?>" style="color:<?= $cat['color'] ?>;margin-right:0.5rem;"></i>
      <?= $cat['category'] ?>
    </span>
  </div>
  <div style="overflow-x:auto;">
    <table class="portal-table">
      <thead>
        <tr>
          <th>File Name</th>
          <th>Type</th>
          <th>Size</th>
          <th>Updated</th>
          <th>Access</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cat['items'] as $item):
          // Accessible if: no product slug required (general doc) OR user has that subscription
          $accessible = $item[3] === null || in_array($item[3], $activeSlugList);
        ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:0.6rem;">
              <i class="fa-solid fa-file-pdf" style="color:#EF4444;font-size:0.9rem;flex-shrink:0;"></i>
              <span style="font-weight:600;color:var(--kw-text-primary);"><?= e($item[0]) ?></span>
            </div>
          </td>
          <td>
            <span style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;padding:0.1rem 0.4rem;font-size:0.68rem;font-weight:700;"><?= $item[1] ?></span>
          </td>
          <td style="color:var(--kw-text-muted);font-size:0.78rem;"><?= $item[2] ?></td>
          <td style="color:var(--kw-text-muted);font-size:0.78rem;"><?= date('d M Y', strtotime($item[4])) ?></td>
          <td>
            <?php if ($accessible): ?>
            <button onclick="simulateDownload(this,'<?= e($item[0]) ?>')" class="kw-btn kw-btn-primary kw-btn-sm" style="font-size:0.72rem;">
              <i class="fa-solid fa-download"></i> Download
            </button>
            <?php else: ?>
            <span style="font-size:0.72rem;color:var(--kw-text-muted);">
              <i class="fa-solid fa-lock" style="margin-right:0.3rem;"></i>
              Subscribe to unlock
            </span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endforeach; ?>

<!-- Request a resource -->
<div class="portal-card" style="border-top:3px solid var(--kw-primary);">
  <div class="portal-card-header">
    <span class="portal-card-title"><i class="fa-solid fa-comment-dots" style="color:var(--kw-primary);margin-right:0.4rem;"></i>Can't Find What You Need?</span>
  </div>
  <p style="font-size:0.82rem;color:var(--kw-text-muted);margin-bottom:1rem;">If you need a specific document, custom report template, or additional training material, raise a support ticket and our team will assist.</p>
  <a href="<?= url('portal/support') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-headset"></i> Request a Resource</a>
</div>

<script>
function simulateDownload(btn, name) {
  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Preparing…';
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = '<i class="fa-solid fa-check"></i> Downloaded!';
    btn.style.background = '#22C55E';
    btn.style.borderColor = '#22C55E';
    setTimeout(() => {
      btn.innerHTML = orig;
      btn.disabled = false;
      btn.style.background = '';
      btn.style.borderColor = '';
    }, 2500);
  }, 1200);
}
</script>
<?php require_once __DIR__ . '/portal-footer.php'; ?>