<?php
/**
 * Krestworks Admin Panel — Shared Header
 * Include at the top of every admin page.
 * Expects: $admin_title (string), $admin_active (string)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

session_name(SESSION_NAME);
if (session_status() === PHP_SESSION_NONE) session_start();

// Admin-only access
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    redirect('admin/login');
}

// Fetch admin user
try {
    $pdo       = db();
    $adminUser = $pdo->prepare('SELECT id, name, email FROM users WHERE id = ? AND role = "admin" LIMIT 1');
    $adminUser->execute([$_SESSION['user_id']]);
    $adminUser = $adminUser->fetch(PDO::FETCH_ASSOC);
    if (!$adminUser) { session_destroy(); redirect('admin/login'); }
} catch (Exception $e) {
    $adminUser = ['name' => 'Admin', 'email' => ''];
}

// Fetch quick counts for badge indicators
try {
    $newInquiries = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE status='new'")->fetchColumn();
    $openTickets  = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status='open'")->fetchColumn();
    $newUsers     = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
} catch(Exception $e) {
    $newInquiries = $openTickets = $newUsers = 0;
}

$admin_active = $admin_active ?? '';
$adminInitials = strtoupper(substr($adminUser['name'], 0, 2));

$navItems = [
    ['dashboard',    'fa-gauge-high',     'Dashboard',    'admin',                           0],
    ['inquiries',    'fa-inbox',          'Inquiries',    'admin/inquiries',                 $newInquiries],
    ['users',        'fa-users',          'Users',        'admin/users',                     $newUsers],
    ['products',     'fa-boxes-stacking', 'Products',     'admin/products',                  0],
    ['ai-tools',     'fa-robot',          'AI Tools',     'admin/ai-tools',                  0],
    ['recruitment',  'fa-user-tie',       'Recruitment',  'admin/recruitment/applications',  0],
    ['community',    'fa-comments',       'Community',    'admin/community',                 0],
    ['support',      'fa-headset',        'Support',      'admin/support',                   $openTickets],
    ['analytics',    'fa-chart-bar',      'Analytics',    'admin/analytics',                 0],
    ['content',      'fa-newspaper',      'Content/Blog', 'admin/content',                   0],
    ['settings',     'fa-sliders',        'Settings',     'admin/settings',                  0],
];
?>
<!DOCTYPE html>
<html lang="en" class="<?= $_COOKIE['kw_theme'] ?? '' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($admin_title ?? 'Admin') ?> — <?= APP_NAME ?> Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
  <link rel="stylesheet" href="<?= asset('css/krest.css') ?>">

  <style>
    :root { --adm-sidebar: 220px; --adm-topbar: 56px; }
    body { background: var(--kw-bg); }

    /* Layout */
    .adm-layout { display: flex; min-height: 100vh; }
    .adm-sidebar { width: var(--adm-sidebar); background: #0A0F1A; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform 0.3s; }
    .adm-main   { margin-left: var(--adm-sidebar); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    .adm-topbar { height: var(--adm-topbar); background: var(--kw-bg-card); border-bottom: 1px solid var(--kw-border); display: flex; align-items: center; justify-content: space-between; padding: 0 1.25rem; position: sticky; top: 0; z-index: 100; }
    .adm-content { flex: 1; padding: 1.5rem; }

    /* Sidebar */
    .adm-logo { padding: 1rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 0.65rem; }
    .adm-logo-mark { width: 28px; height: 28px; background: var(--kw-primary); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; color: #0A0F1A; flex-shrink: 0; }
    .adm-logo-text { font-size: 0.78rem; font-weight: 700; color: #fff; }
    .adm-logo-sub  { font-size: 0.6rem; color: rgba(255,255,255,0.35); }

    .adm-nav { flex: 1; padding: 0.75rem 0.65rem; overflow-y: auto; }
    .adm-nav-label { font-size: 0.58rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: rgba(255,255,255,0.25); padding: 0.65rem 0.5rem 0.3rem; }
    .adm-nav-item { display: flex; align-items: center; gap: 0.6rem; padding: 0.55rem 0.7rem; border-radius: 6px; font-size: 0.78rem; font-weight: 500; color: rgba(255,255,255,0.55); text-decoration: none; transition: all 0.15s; margin-bottom: 0.1rem; position: relative; }
    .adm-nav-item:hover  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
    .adm-nav-item.active { background: rgba(245,168,0,0.15); color: #F5A800; }
    .adm-nav-item i      { width: 14px; text-align: center; font-size: 0.78rem; flex-shrink: 0; }
    .adm-badge { background: #EF4444; color: #fff; border-radius: 999px; font-size: 0.58rem; font-weight: 800; padding: 0.1rem 0.4rem; min-width: 16px; text-align: center; position: absolute; right: 0.6rem; }

    .adm-user-bar { padding: 0.75rem 1rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 0.55rem; }
    .adm-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--kw-primary); color: #0A0F1A; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800; flex-shrink: 0; }

    /* Cards */
    .adm-card { background: var(--kw-bg-card); border: 1px solid var(--kw-border); border-radius: var(--kw-radius-xl); }
    .adm-card-head { display: flex; align-items: center; justify-content: space-between; padding: 0.9rem 1.25rem; border-bottom: 1px solid var(--kw-border); }
    .adm-card-title { font-size: 0.875rem; font-weight: 700; margin: 0; }
    .adm-card-body { padding: 1.25rem; }

    /* Stat cards */
    .adm-stat { background: var(--kw-bg-card); border: 1px solid var(--kw-border); border-radius: var(--kw-radius-xl); padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 0.85rem; }
    .adm-stat-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem; }
    .adm-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--kw-text-primary); font-family: var(--font-heading); line-height: 1; }
    .adm-stat-label { font-size: 0.7rem; color: var(--kw-text-muted); margin-top: 0.15rem; }

    /* Tables */
    .adm-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .adm-table th { text-align: left; padding: 0.6rem 1rem; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--kw-text-muted); border-bottom: 1px solid var(--kw-border); background: var(--kw-bg-alt); white-space: nowrap; }
    .adm-table td { padding: 0.75rem 1rem; border-bottom: 1px solid var(--kw-border); color: var(--kw-text-secondary); vertical-align: middle; }
    .adm-table tr:last-child td { border-bottom: none; }
    .adm-table tr:hover td { background: var(--kw-bg-alt); }

    /* Badges */
    .adm-badge-inline { display: inline-flex; align-items: center; padding: 0.15rem 0.55rem; border-radius: 999px; font-size: 0.65rem; font-weight: 700; }
    .badge-new       { background: rgba(59,130,246,0.12); color: #3B82F6; }
    .badge-active    { background: rgba(34,197,94,0.12);  color: #22C55E; }
    .badge-resolved  { background: rgba(107,114,128,0.12);color: #6B7280; }
    .badge-open      { background: rgba(245,168,0,0.12);  color: #F5A800; }
    .badge-danger    { background: rgba(239,68,68,0.12);  color: #EF4444; }
    .badge-info      { background: rgba(59,130,246,0.12); color: #3B82F6; }
    .badge-admin     { background: rgba(245,168,0,0.12);  color: #F5A800; }
    .badge-client    { background: rgba(107,114,128,0.12);color: #6B7280; }

    /* Form controls in admin */
    .adm-input { width: 100%; padding: 0.55rem 0.85rem; background: var(--kw-bg-alt); border: 1px solid var(--kw-border); border-radius: var(--kw-radius-md); font-size: 0.82rem; color: var(--kw-text-primary); outline: none; font-family: inherit; }
    .adm-input:focus { border-color: var(--kw-primary); }
    .adm-label { display: block; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--kw-text-muted); margin-bottom: 0.35rem; }
    .adm-select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 16px; padding-right: 2rem; }

    /* Mobile */
    .adm-sidebar-toggle { display: none; background: none; border: none; cursor: pointer; color: var(--kw-text-primary); font-size: 1rem; }
    .adm-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 199; }

    @media(max-width: 1024px) {
      .adm-sidebar { transform: translateX(-100%); }
      .adm-sidebar.open { transform: translateX(0); }
      .adm-main { margin-left: 0; }
      .adm-sidebar-toggle { display: block; }
      .adm-overlay.show { display: block; }
    }
    @media(max-width: 640px) {
      .adm-content { padding: 1rem; }
    }
  </style>
</head>
<body>

<div class="adm-overlay" id="adm-overlay" onclick="admCloseSidebar()"></div>

<div class="adm-layout">

  <!-- Sidebar -->
  <aside class="adm-sidebar" id="adm-sidebar">
    <div class="adm-logo">
      <div class="adm-logo-mark">KC</div>
      <div>
        <div class="adm-logo-text"><?= APP_NAME ?></div>
        <div class="adm-logo-sub">Admin Panel</div>
      </div>
    </div>

    <nav class="adm-nav">
      <div class="adm-nav-label">Main</div>
      <?php foreach ($navItems as $item): ?>
      <a href="<?= url($item[3]) ?>" class="adm-nav-item <?= $admin_active === $item[0] ? 'active' : '' ?>">
        <i class="fa-solid <?= $item[1] ?>"></i>
        <?= $item[2] ?>
        <?php if ($item[4] > 0): ?><span class="adm-badge"><?= $item[4] > 99 ? '99+' : $item[4] ?></span><?php endif; ?>
      </a>
      <?php endforeach; ?>

      <div class="adm-nav-label" style="margin-top:0.75rem;">Site</div>
      <a href="<?= url() ?>" target="_blank" class="adm-nav-item">
        <i class="fa-solid fa-globe"></i> View Website
      </a>
      <a href="<?= url('portal') ?>" target="_blank" class="adm-nav-item">
        <i class="fa-solid fa-user-circle"></i> Client Portal
      </a>
    </nav>

    <div class="adm-user-bar">
      <div class="adm-avatar"><?= e($adminInitials) ?></div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:0.72rem;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($adminUser['name']) ?></div>
        <div style="font-size:0.6rem;color:rgba(255,255,255,0.35);">Administrator</div>
      </div>
      <a href="<?= url('api/auth-logout') ?>" title="Logout" style="color:rgba(255,255,255,0.3);font-size:0.82rem;text-decoration:none;flex-shrink:0;">
        <i class="fa-solid fa-right-from-bracket"></i>
      </a>
    </div>
  </aside>

  <!-- Main area -->
  <div class="adm-main">
    <!-- Topbar -->
    <header class="adm-topbar">
      <div style="display:flex;align-items:center;gap:0.75rem;">
        <button class="adm-sidebar-toggle" onclick="admOpenSidebar()"><i class="fa-solid fa-bars"></i></button>
        <h2 style="font-size:0.875rem;font-weight:700;margin:0;"><?= e($admin_title ?? 'Admin') ?></h2>
      </div>
      <div style="display:flex;align-items:center;gap:0.75rem;">
        <button onclick="KrestTheme?.toggle()" style="background:none;border:none;color:var(--kw-text-muted);cursor:pointer;font-size:0.82rem;" title="Toggle theme">
          <i class="fa-solid fa-circle-half-stroke"></i>
        </button>
        <?php if ($newInquiries > 0): ?>
        <a href="<?= url('admin/inquiries') ?>" style="position:relative;color:var(--kw-text-muted);font-size:0.875rem;text-decoration:none;" title="<?= $newInquiries ?> new inquiries">
          <i class="fa-solid fa-bell"></i>
          <span style="position:absolute;top:-4px;right:-4px;width:14px;height:14px;border-radius:50%;background:#EF4444;color:#fff;font-size:0.5rem;font-weight:800;display:flex;align-items:center;justify-content:center;"><?= min($newInquiries,9) ?></span>
        </a>
        <?php endif; ?>
        <a href="<?= url('admin/settings') ?>" style="color:var(--kw-text-muted);font-size:0.82rem;text-decoration:none;" title="Settings">
          <i class="fa-solid fa-sliders"></i>
        </a>
        <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--kw-text-muted);">
          <div class="adm-avatar" style="width:24px;height:24px;font-size:0.55rem;"><?= e($adminInitials) ?></div>
          <span><?= e(explode(' ', $adminUser['name'])[0]) ?></span>
        </div>
      </div>
    </header>

    <!-- Page content begins -->
    <div class="adm-content">