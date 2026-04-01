<?php
/**
 * Krestworks Client Portal — Shared Header/Layout
 * Include at top of every portal page.
 * Expects: $portal_title, $portal_active
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/csrf.php';

requireLogin();
session_start_safe();

$userId    = $_SESSION['user_id'];
$userName  = $_SESSION['user_name']  ?? 'Client';
$userEmail = $_SESSION['user_email'] ?? '';
$userRole  = $_SESSION['user_role']  ?? 'client';

// Redirect admins to admin panel
if ($userRole === 'admin') { redirect('admin'); }

$portalTitle  = $portal_title  ?? 'Portal';
$portalActive = $portal_active ?? '';

$navItems = [
  ['dashboard',     'fa-gauge',         'Dashboard',     url('portal')],
  ['subscriptions', 'fa-layer-group',   'Subscriptions', url('portal/subscriptions')],
  ['downloads',     'fa-download',      'Downloads',     url('portal/downloads')],
  ['applications',  'fa-file-alt',      'My Applications',url('portal/applications')],
  ['training',      'fa-graduation-cap','Training',      url('portal/training')],
  ['support',       'fa-headset',       'Support',       url('portal/support')],
  ['settings',      'fa-gear',          'Settings',      url('portal/settings')],
];
?>
<!DOCTYPE html>
<html lang="en" id="kw-html">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($portalTitle) ?> — Client Portal | <?= APP_NAME ?></title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/krest.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
  <meta name="csrf-token" content="<?= csrfGenerate() ?>">
  <style>
    :root {
      --portal-sidebar: 240px;
      --portal-topbar: 60px;
    }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: var(--font-body); background: var(--kw-bg-alt); color: var(--kw-text-primary); }

    /* Sidebar */
    .portal-sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: var(--portal-sidebar);
      background: var(--kw-bg-card); border-right: 1px solid var(--kw-border);
      display: flex; flex-direction: column; z-index: 200; transition: transform 0.25s;
    }
    .portal-sidebar-logo {
      padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--kw-border);
      display: flex; align-items: center; gap: 0.75rem;
    }
    .portal-sidebar-logo .logo-mark {
      width: 36px; height: 36px; border-radius: 8px; background: var(--kw-primary);
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-heading); font-weight: 900; font-size: 0.85rem; color: #0A0F1A; flex-shrink: 0;
    }
    .portal-sidebar-logo span { font-weight: 700; font-size: 0.875rem; line-height: 1.2; }
    .portal-sidebar-logo small { font-size: 0.65rem; color: var(--kw-primary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }

    .portal-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
    .portal-nav-section { padding: 0 1rem 0.5rem; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--kw-text-muted); margin-top: 0.75rem; }
    .portal-nav-item {
      display: flex; align-items: center; gap: 0.65rem; padding: 0.6rem 1rem; margin: 0.1rem 0.65rem;
      border-radius: var(--kw-radius-md); font-size: 0.82rem; font-weight: 500;
      color: var(--kw-text-secondary); text-decoration: none; transition: all 0.15s;
    }
    .portal-nav-item:hover { background: var(--kw-bg-alt); color: var(--kw-text-primary); }
    .portal-nav-item.active { background: rgba(245,168,0,0.12); color: var(--kw-primary); font-weight: 700; }
    .portal-nav-item i { width: 16px; font-size: 0.85rem; flex-shrink: 0; }
    .portal-nav-item .badge { margin-left: auto; background: var(--kw-primary); color: #0A0F1A; border-radius: 999px; padding: 0.1rem 0.45rem; font-size: 0.62rem; font-weight: 800; }

    .portal-sidebar-footer { padding: 1rem; border-top: 1px solid var(--kw-border); }
    .portal-user-mini { display: flex; align-items: center; gap: 0.65rem; padding: 0.5rem; border-radius: var(--kw-radius-md); }
    .portal-user-mini .avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--kw-primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; color: #0A0F1A; flex-shrink: 0; }
    .portal-user-mini .info { flex: 1; min-width: 0; }
    .portal-user-mini .info strong { font-size: 0.78rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
    .portal-user-mini .info small { font-size: 0.65rem; color: var(--kw-text-muted); }

    /* Top bar */
    .portal-topbar {
      position: fixed; top: 0; left: var(--portal-sidebar); right: 0; height: var(--portal-topbar);
      background: var(--kw-bg-card); border-bottom: 1px solid var(--kw-border);
      display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem;
      z-index: 100;
    }
    .portal-topbar .breadcrumb-area { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: var(--kw-text-muted); }
    .portal-topbar .breadcrumb-area strong { color: var(--kw-text-primary); }
    .portal-topbar .topbar-actions { display: flex; align-items: center; gap: 0.75rem; }
    .portal-topbar-btn { width: 34px; height: 34px; border-radius: var(--kw-radius-md); background: var(--kw-bg-alt); border: 1px solid var(--kw-border); display: flex; align-items: center; justify-content: center; color: var(--kw-text-muted); cursor: pointer; font-size: 0.82rem; text-decoration: none; transition: all 0.15s; position: relative; }
    .portal-topbar-btn:hover { color: var(--kw-primary); border-color: var(--kw-primary); }
    .portal-topbar-btn .notif-dot { position: absolute; top: -3px; right: -3px; width: 8px; height: 8px; border-radius: 50%; background: var(--kw-primary); border: 2px solid var(--kw-bg-card); }

    /* Mobile menu toggle */
    .portal-menu-toggle { display: none; }

    /* Main content */
    .portal-main {
      margin-left: var(--portal-sidebar);
      padding-top: var(--portal-topbar);
      min-height: 100vh;
    }
    .portal-content { padding: 2rem; }

    /* Page header */
    .portal-page-header { margin-bottom: 1.75rem; display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .portal-page-header h1 { font-size: 1.35rem; font-family: var(--font-heading); margin: 0 0 0.2rem; }
    .portal-page-header p { font-size: 0.82rem; color: var(--kw-text-muted); margin: 0; }

    /* Stats row */
    .portal-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.75rem; }
    .portal-stat-card { background: var(--kw-bg-card); border: 1px solid var(--kw-border); border-radius: var(--kw-radius-lg); padding: 1.25rem; }
    .portal-stat-card .stat-value { font-size: 1.75rem; font-weight: 800; font-family: var(--font-heading); color: var(--kw-text-primary); line-height: 1; margin-bottom: 0.3rem; }
    .portal-stat-card .stat-label { font-size: 0.75rem; color: var(--kw-text-muted); font-weight: 500; }
    .portal-stat-card .stat-icon { font-size: 1rem; margin-bottom: 0.65rem; }

    /* Card */
    .portal-card { background: var(--kw-bg-card); border: 1px solid var(--kw-border); border-radius: var(--kw-radius-xl); padding: 1.5rem; margin-bottom: 1.25rem; }
    .portal-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; }
    .portal-card-title { font-size: 0.9rem; font-weight: 700; }
    .portal-card-actions { display: flex; gap: 0.5rem; }

    /* Table */
    .portal-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .portal-table th { padding: 0.65rem 0.85rem; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--kw-text-muted); border-bottom: 1px solid var(--kw-border); }
    .portal-table td { padding: 0.85rem; border-bottom: 1px solid var(--kw-border); color: var(--kw-text-secondary); vertical-align: middle; }
    .portal-table tr:last-child td { border-bottom: none; }
    .portal-table tr:hover td { background: var(--kw-bg-alt); }

    /* Status badges */
    .status-badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.65rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
    .status-active { background: #22C55E15; color: #22C55E; }
    .status-expired { background: #EF444415; color: #EF4444; }
    .status-pending { background: #F5A80015; color: #F5A800; }
    .status-resolved { background: #22C55E15; color: #22C55E; }
    .status-open { background: #3B82F615; color: #3B82F6; }
    .status-in-progress { background: #A855F715; color: #A855F7; }
    .status-closed { background: #6B728015; color: #6B7280; }

    /* Mobile responsive */
    @media (max-width: 900px) {
      .portal-sidebar { transform: translateX(-100%); }
      .portal-sidebar.open { transform: translateX(0); }
      .portal-main { margin-left: 0; }
      .portal-topbar { left: 0; }
      .portal-menu-toggle { display: flex; }
      .portal-content { padding: 1.25rem; }
    }
    @media (max-width: 640px) {
      .portal-stats { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

<!-- Portal Sidebar -->
<aside class="portal-sidebar" id="portal-sidebar">
  <div class="portal-sidebar-logo">
    <a href="<?= url('portal') ?>" style="display:flex;align-items:center;gap:0.75rem;text-decoration:none;color:inherit;">
      <div class="logo-mark">KC</div>
      <div>
        <span><?= APP_NAME ?></span><br>
        <small>Client Portal</small>
      </div>
    </a>
  </div>

  <nav class="portal-nav">
    <div class="portal-nav-section">Main</div>
    <?php foreach ($navItems as $item): ?>
    <a href="<?= $item[3] ?>" class="portal-nav-item <?= $portalActive === $item[0] ? 'active' : '' ?>">
      <i class="fa-solid <?= $item[1] ?>"></i>
      <?= $item[2] ?>
      <?php if ($item[0] === 'support'): ?>
        <?php
        try {
          $openTickets = db()->prepare("SELECT COUNT(*) FROM support_tickets WHERE user_id = ? AND status NOT IN ('closed','resolved')");
          $openTickets->execute([$userId]);
          $tc = (int)$openTickets->fetchColumn();
          if ($tc > 0) echo "<span class='badge'>$tc</span>";
        } catch(Exception $e) {}
        ?>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>

    <div class="portal-nav-section" style="margin-top:1rem;">Quick Links</div>
    <a href="<?= url('products') ?>" class="portal-nav-item" target="_blank">
      <i class="fa-solid fa-boxes-stacking"></i> All Products
    </a>
    <a href="<?= url('demo') ?>" class="portal-nav-item" target="_blank">
      <i class="fa-solid fa-play-circle"></i> Book Demo
    </a>
    <a href="<?= url('ai-hub') ?>" class="portal-nav-item" target="_blank">
      <i class="fa-solid fa-robot"></i> AI Hub
    </a>
    <a href="<?= url('community') ?>" class="portal-nav-item" target="_blank">
      <i class="fa-solid fa-users"></i> Community
    </a>
  </nav>

  <div class="portal-sidebar-footer">
    <div class="portal-user-mini">
      <div class="avatar"><?= strtoupper(mb_substr($userName, 0, 1)) ?></div>
      <div class="info">
        <strong><?= e($userName) ?></strong>
        <small>Client Account</small>
      </div>
    </div>
    <a href="<?= url('api/auth-logout') ?>" style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem;border-radius:var(--kw-radius-md);font-size:0.78rem;color:#EF4444;text-decoration:none;margin-top:0.35rem;transition:background 0.15s;" onmouseover="this.style.background='#EF444410'" onmouseout="this.style.background='transparent'">
      <i class="fa-solid fa-right-from-bracket" style="width:16px;font-size:0.8rem;"></i> Sign Out
    </a>
  </div>
</aside>

<!-- Top bar -->
<header class="portal-topbar">
  <div style="display:flex;align-items:center;gap:0.85rem;">
    <button class="portal-menu-toggle portal-topbar-btn" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="breadcrumb-area">
      <a href="<?= url('portal') ?>" style="color:var(--kw-text-muted);text-decoration:none;">Portal</a>
      <?php if ($portalActive !== 'dashboard'): ?>
      <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
      <strong><?= e($portalTitle) ?></strong>
      <?php endif; ?>
    </div>
  </div>
  <div class="topbar-actions">
    <!-- Theme toggle -->
    <button class="portal-topbar-btn" onclick="KrestTheme.toggle()" title="Toggle theme">
      <i class="fa-solid fa-circle-half-stroke"></i>
    </button>
    <!-- Notifications bell -->
    <button class="portal-topbar-btn" onclick="window.location.href='<?= url('portal/support') ?>'" title="Support tickets">
      <i class="fa-solid fa-bell"></i>
      <?php
      try {
        $notifs = db()->prepare("SELECT COUNT(*) FROM support_tickets WHERE user_id = ? AND status = 'open'");
        $notifs->execute([$userId]);
        if ((int)$notifs->fetchColumn() > 0) echo '<span class="notif-dot"></span>';
      } catch(Exception $e) {}
      ?>
    </button>
    <!-- User dropdown -->
    <div style="position:relative;" id="user-dropdown-wrap">
      <button class="portal-topbar-btn" onclick="toggleDropdown()" style="width:auto;padding:0 0.75rem;gap:0.5rem;font-size:0.78rem;font-weight:600;">
        <div style="width:24px;height:24px;border-radius:50%;background:var(--kw-primary);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;color:#0A0F1A;flex-shrink:0;">
          <?= strtoupper(mb_substr($userName, 0, 1)) ?>
        </div>
        <?= e(explode(' ', $userName)[0]) ?>
        <i class="fa-solid fa-chevron-down" style="font-size:0.6rem;"></i>
      </button>
      <div id="user-dropdown" style="display:none;position:absolute;right:0;top:calc(100%+8px);background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);min-width:180px;z-index:999;box-shadow:0 8px 24px rgba(0,0,0,0.15);overflow:hidden;">
        <div style="padding:0.85rem 1rem;border-bottom:1px solid var(--kw-border);">
          <div style="font-size:0.82rem;font-weight:700;"><?= e($userName) ?></div>
          <div style="font-size:0.7rem;color:var(--kw-text-muted);"><?= e($userEmail) ?></div>
        </div>
        <?php foreach ([
          [url('portal/settings'),'fa-gear','Account Settings'],
          [url('portal/support'),'fa-headset','Support'],
          [url(''),'fa-globe','Back to Website'],
        ] as $di): ?>
        <a href="<?= $di[0] ?>" style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 1rem;font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='var(--kw-bg-alt)'" onmouseout="this.style.background=''">
          <i class="fa-solid <?= $di[1] ?>" style="width:14px;color:var(--kw-text-muted);font-size:0.78rem;"></i><?= $di[2] ?>
        </a>
        <?php endforeach; ?>
        <div style="border-top:1px solid var(--kw-border);">
          <a href="<?= url('api/auth-logout') ?>" style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 1rem;font-size:0.82rem;color:#EF4444;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#EF444408'" onmouseout="this.style.background=''">
            <i class="fa-solid fa-right-from-bracket" style="width:14px;font-size:0.78rem;"></i>Sign Out
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Main content wrapper -->
<main class="portal-main">
<div class="portal-content">

<script src="<?= asset('js/krest-theme.js') ?>"></script>
<script>
function toggleSidebar() {
  document.getElementById('portal-sidebar').classList.toggle('open');
}
function toggleDropdown() {
  const d = document.getElementById('user-dropdown');
  d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', e => {
  const wrap = document.getElementById('user-dropdown-wrap');
  if (wrap && !wrap.contains(e.target)) document.getElementById('user-dropdown').style.display = 'none';
});
</script>