<?php
$portal_title  = 'Subscriptions & Licences';
$portal_active = 'subscriptions';
require_once __DIR__ . '/portal-header.php';

$pdo = db();

// All subscriptions for this user
$stmt = $pdo->prepare('
  SELECT s.*, p.name as product_name, p.slug as product_slug, p.description as product_desc
  FROM subscriptions s
  LEFT JOIN products p ON p.id = s.product_id
  WHERE s.user_id = ?
  ORDER BY FIELD(s.status,"active","pending","expired","cancelled"), s.created_at DESC
');
$stmt->execute([$userId]);
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$allProducts = [
  ['hr-system','HR Management System','fa-users','#F5A800'],
  ['procurement-system','Procurement Management','fa-boxes-stacking','#3B82F6'],
  ['elearning-system','eLearning Management','fa-graduation-cap','#22C55E'],
  ['real-estate-system','Real Estate Management','fa-building','#A855F7'],
  ['supply-chain-system','Supply Chain Management','fa-truck','#F97316'],
  ['decision-support-system','Decision Support System','fa-chart-bar','#EF4444'],
  ['crm-system','CRM System','fa-handshake','#06B6D4'],
  ['hospital-system','Hospital Management','fa-hospital','#8B5CF6'],
  ['pos-system','Point of Sale System','fa-cash-register','#F59E0B'],
];
?>

<div class="portal-page-header">
  <div>
    <h1>Subscriptions & Licences</h1>
    <p>Manage your active systems, renew subscriptions, and explore additional products.</p>
  </div>
  <a href="<?= url('products') ?>" target="_blank" class="kw-btn kw-btn-primary kw-btn-sm">
    <i class="fa-solid fa-plus"></i> Add System
  </a>
</div>

<?php if (empty($subscriptions)): ?>
<!-- Empty state -->
<div class="portal-card" style="text-align:center;padding:4rem 2rem;">
  <i class="fa-solid fa-box-open" style="font-size:3rem;color:var(--kw-primary);opacity:0.4;margin-bottom:1.25rem;display:block;"></i>
  <h3 style="margin-bottom:0.5rem;">No Active Subscriptions</h3>
  <p style="color:var(--kw-text-muted);font-size:0.875rem;max-width:400px;margin:0 auto 1.5rem;">You don't have any subscriptions yet. Explore our enterprise systems or contact us to get started.</p>
  <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="<?= url('products') ?>" target="_blank" class="kw-btn kw-btn-primary"><i class="fa-solid fa-boxes-stacking"></i> Browse Products</a>
    <a href="<?= url('demo') ?>" target="_blank" class="kw-btn kw-btn-ghost"><i class="fa-solid fa-play-circle"></i> Book Demo</a>
  </div>
</div>
<?php else: ?>

<!-- Summary stats -->
<?php
$activeCount = count(array_filter($subscriptions, fn($s) => $s['status'] === 'active'));
$expiringSoon = count(array_filter($subscriptions, fn($s) => $s['expires_at'] && strtotime($s['expires_at']) < strtotime('+30 days') && $s['status'] === 'active'));
?>
<div class="portal-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem;">
  <div class="portal-stat-card">
    <div class="stat-icon"><i class="fa-solid fa-check-circle" style="color:#22C55E;"></i></div>
    <div class="stat-value" style="color:#22C55E;"><?= $activeCount ?></div>
    <div class="stat-label">Active Subscriptions</div>
  </div>
  <div class="portal-stat-card">
    <div class="stat-icon"><i class="fa-solid fa-clock" style="color:#F5A800;"></i></div>
    <div class="stat-value" style="color:#F5A800;"><?= $expiringSoon ?></div>
    <div class="stat-label">Expiring in 30 Days</div>
  </div>
  <div class="portal-stat-card">
    <div class="stat-icon"><i class="fa-solid fa-layer-group" style="color:#3B82F6;"></i></div>
    <div class="stat-value" style="color:#3B82F6;"><?= count($subscriptions) ?></div>
    <div class="stat-label">Total Systems</div>
  </div>
</div>

<!-- Subscription cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.25rem;margin-bottom:2rem;">
  <?php foreach ($subscriptions as $sub):
    $isActive = $sub['status'] === 'active';
    $daysLeft  = $sub['expires_at'] ? ceil((strtotime($sub['expires_at']) - time()) / 86400) : null;
    $urgent    = $daysLeft !== null && $daysLeft <= 14 && $isActive;
    $picon = 'fa-cube'; $pcolor = '#F5A800';
    foreach ($allProducts as $ap) { if ($ap[0] === $sub['product_slug']) { $picon = $ap[2]; $pcolor = $ap[3]; break; } }
  ?>
  <div class="portal-card" style="border-top:3px solid <?= $urgent ? '#EF4444' : ($isActive ? '#22C55E' : 'var(--kw-border)') ?>;">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem;">
      <div style="display:flex;align-items:center;gap:0.75rem;">
        <div style="width:44px;height:44px;border-radius:var(--kw-radius-md);background:<?= $pcolor ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="fa-solid <?= $picon ?>" style="font-size:1.1rem;color:<?= $pcolor ?>;"></i>
        </div>
        <div>
          <div style="font-size:0.9rem;font-weight:700;"><?= e($sub['product_name'] ?? 'System') ?></div>
          <span class="status-badge status-<?= $sub['status'] ?>" style="font-size:0.62rem;"><?= ucfirst($sub['status']) ?></span>
        </div>
      </div>
      <?php if ($sub['product_slug']): ?>
      <a href="<?= url('products/'.$sub['product_slug']) ?>" target="_blank" style="font-size:0.72rem;color:var(--kw-primary);text-decoration:none;" title="View product">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      </a>
      <?php endif; ?>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:1rem;">
      <?php foreach ([
        ['Plan', ucfirst($sub['plan'] ?? 'Standard')],
        ['Billing', ucfirst($sub['billing_cycle'] ?? 'Monthly')],
        ['Started', date('d M Y', strtotime($sub['created_at']))],
        ['Expires', $sub['expires_at'] ? date('d M Y', strtotime($sub['expires_at'])) : 'Ongoing'],
      ] as $info): ?>
      <div>
        <div style="font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);"><?= $info[0] ?></div>
        <div style="font-size:0.8rem;font-weight:600;"><?= e($info[1]) ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($daysLeft !== null && $isActive): ?>
    <div style="margin-bottom:1rem;">
      <div style="display:flex;justify-content:space-between;font-size:0.72rem;margin-bottom:0.3rem;">
        <span style="color:var(--kw-text-muted);">Subscription period</span>
        <span style="font-weight:700;color:<?= $urgent ? '#EF4444' : 'var(--kw-text-primary)' ?>;">
          <?= $daysLeft > 0 ? "$daysLeft days left" : 'Expired' ?>
        </span>
      </div>
      <?php $totalDays = max(1, ceil((strtotime($sub['expires_at']) - strtotime($sub['created_at'])) / 86400)); $pct = max(0, min(100, round(($totalDays - max(0,$daysLeft)) / $totalDays * 100))); ?>
      <div style="height:4px;background:var(--kw-border);border-radius:2px;overflow:hidden;">
        <div style="height:100%;width:<?= $pct ?>%;background:<?= $urgent ? '#EF4444' : '#22C55E' ?>;border-radius:2px;transition:width 0.5s;"></div>
      </div>
    </div>
    <?php endif; ?>

    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
      <?php if ($isActive): ?>
        <a href="<?= url('portal/support') ?>?subject=Renewal: '.e($sub['product_name'])" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-rotate"></i> Renew</a>
        <a href="<?= url('portal/downloads') ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-download"></i> Downloads</a>
      <?php else: ?>
        <a href="<?= url('consultation') ?>" target="_blank" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-refresh"></i> Reactivate</a>
      <?php endif; ?>
      <a href="<?= url('portal/support') ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-headset"></i> Support</a>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Available products to add -->
<div class="portal-card">
  <div class="portal-card-header">
    <span class="portal-card-title"><i class="fa-solid fa-boxes-stacking" style="color:var(--kw-primary);margin-right:0.4rem;"></i>Explore More Systems</span>
    <a href="<?= url('products') ?>" target="_blank" style="font-size:0.73rem;color:var(--kw-primary);text-decoration:none;">View all →</a>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.75rem;">
    <?php foreach ($allProducts as $ap):
      // Check if already subscribed
      $alreadyHas = in_array($ap[0], array_column($subscriptions, 'product_slug'));
    ?>
    <div style="padding:1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);display:flex;flex-direction:column;gap:0.5rem;">
      <div style="display:flex;align-items:center;gap:0.5rem;">
        <i class="fa-solid <?= $ap[2] ?>" style="color:<?= $ap[3] ?>;font-size:0.9rem;"></i>
        <span style="font-size:0.78rem;font-weight:700;"><?= $ap[1] ?></span>
      </div>
      <?php if ($alreadyHas): ?>
        <span style="font-size:0.68rem;color:#22C55E;font-weight:700;"><i class="fa-solid fa-check" style="margin-right:0.2rem;"></i>Already active</span>
      <?php else: ?>
        <a href="<?= url('demo') ?>?product='.urlencode($ap[1])" target="_blank" style="font-size:0.7rem;color:var(--kw-primary);font-weight:700;text-decoration:none;">Request demo →</a>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php require_once __DIR__ . '/portal-footer.php'; ?>