<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

// Page meta defaults (pages can override before including header)
$page_title       = $page_title       ?? APP_NAME . ' — ' . APP_TAGLINE;
$page_description = $page_description ?? APP_DESCRIPTION;
$page_canonical   = $page_canonical   ?? BASE_URL . '/' . trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
$page_og_image    = $page_og_image    ?? BASE_URL . '/assets/img/og-default.png';
?>
<!DOCTYPE html>
<html lang="en" class="<?= !empty($_COOKIE['kw_theme']) && $_COOKIE['kw_theme'] === 'dark' ? 'dark-mode-preload' : '' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= e($page_title) ?></title>
  <meta name="description" content="<?= e($page_description) ?>">
  <link rel="canonical" href="<?= e($page_canonical) ?>">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= e($page_title) ?>">
  <meta property="og:description" content="<?= e($page_description) ?>">
  <meta property="og:url"         content="<?= e($page_canonical) ?>">
  <meta property="og:image"       content="<?= e($page_og_image) ?>">
  <meta property="og:site_name"   content="<?= e(APP_NAME) ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= e($page_title) ?>">
  <meta name="twitter:description" content="<?= e($page_description) ?>">
  <meta name="twitter:image"       content="<?= e($page_og_image) ?>">

  <!-- CSRF Meta -->
  <meta name="csrf-token" content="<?= e(csrfGenerate()) ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= asset('img/favicon.png') ?>">
  <link rel="apple-touch-icon"      href="<?= asset('img/apple-touch-icon.png') ?>">

  <!-- Fonts (preconnect first) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

  <!-- AOS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" crossorigin="anonymous">

  <!-- Core Styles -->
  <link rel="stylesheet" href="<?= asset('css/krest.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/components.css') ?>">

  <?php if (!empty($extra_css)): ?>
    <?php foreach ($extra_css as $css): ?>
      <link rel="stylesheet" href="<?= e($css) ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- Theme preload (prevents flash) -->
  <script>
    (function() {
      var t = localStorage.getItem('kw_theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      if (t === 'dark') document.body && document.body.classList.add('dark-theme');
      document.addEventListener('DOMContentLoaded', function() {
        if (t === 'dark') document.body.classList.add('dark-theme');
      });
    })();
  </script>
</head>
<body>

<?php include __DIR__ . '/nav.php'; ?>

<!-- Toast container -->
<div id="kw-toast-container" aria-live="polite" aria-atomic="true"></div>