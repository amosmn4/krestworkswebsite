<?php
require_once __DIR__ . '/helpers.php';
?>
<nav id="kw-nav" role="navigation" aria-label="Main navigation">
  <div class="kw-container">
    <div class="nav-inner">

      <!-- Logo -->
      <a href="<?= url() ?>" class="nav-logo" aria-label="<?= e(APP_NAME) ?> Home">
        <img src="<?= asset('img/logo.png') ?>" alt="<?= e(APP_NAME) ?> Logo" width="38" height="38">
        <span class="nav-logo-text">Krest<span>works</span></span>
      </a>

      <!-- Desktop Links -->
      <ul class="nav-links" role="list">

        <li>
          <a href="<?= url() ?>" class="nav-link <?= isActivePage('') ?>">Home</a>
        </li>

        <!-- Services Dropdown -->
        <li class="nav-dropdown">
          <a href="<?= url('services') ?>" class="nav-link <?= isActiveSection('services') ?>">Services</a>
          <ul class="nav-dropdown-menu" role="list">
            <li><a href="<?= url('services/custom-software') ?>"><i class="fa-solid fa-laptop-code"></i> Custom Software</a></li>
            <li><a href="<?= url('services/web-development') ?>"><i class="fa-solid fa-globe"></i> Web Development</a></li>
            <li><a href="<?= url('services/mobile-development') ?>"><i class="fa-solid fa-mobile-alt"></i> Mobile Development</a></li>
            <li><a href="<?= url('services/system-integration') ?>"><i class="fa-solid fa-plug"></i> System Integration</a></li>
            <li><a href="<?= url('services/ai-solutions') ?>"><i class="fa-solid fa-robot"></i> AI Solutions</a></li>
            <li><a href="<?= url('services/consulting') ?>"><i class="fa-solid fa-lightbulb"></i> Consulting</a></li>
            <li><a href="<?= url('services/cloud-infrastructure') ?>"><i class="fa-solid fa-cloud"></i> Cloud Infrastructure</a></li>
            <li><a href="<?= url('services/cybersecurity') ?>"><i class="fa-solid fa-shield-halved"></i> Cybersecurity</a></li>
          </ul>
        </li>

        <!-- Products Dropdown -->
        <li class="nav-dropdown">
          <a href="<?= url('products') ?>" class="nav-link <?= isActiveSection('products') ?>">Products</a>
          <ul class="nav-dropdown-menu" role="list">
            <li><a href="<?= url('products/hr-system') ?>"><i class="fa-solid fa-users"></i> HR Management</a></li>
            <li><a href="<?= url('products/procurement-system') ?>"><i class="fa-solid fa-shopping-cart"></i> Procurement</a></li>
            <li><a href="<?= url('products/elearning-system') ?>"><i class="fa-solid fa-graduation-cap"></i> eLearning</a></li>
            <li><a href="<?= url('products/real-estate-system') ?>"><i class="fa-solid fa-building"></i> Real Estate</a></li>
            <li><a href="<?= url('products/supply-chain-system') ?>"><i class="fa-solid fa-truck"></i> Supply Chain</a></li>
            <li><a href="<?= url('products/crm-system') ?>"><i class="fa-solid fa-handshake"></i> CRM</a></li>
            <li><a href="<?= url('products/hospital-system') ?>"><i class="fa-solid fa-hospital"></i> Hospital System</a></li>
            <li><a href="<?= url('products') ?>" style="color:var(--kw-primary);font-weight:700;"><i class="fa-solid fa-grid-2"></i> View All Products →</a></li>
          </ul>
        </li>

        <!-- AI Hub -->
        <li class="nav-dropdown">
          <a href="<?= url('ai-hub') ?>" class="nav-link <?= isActiveSection('ai-hub') ?>">AI Hub</a>
          <ul class="nav-dropdown-menu" role="list">
            <li><a href="<?= url('ai-hub/document-summarizer') ?>"><i class="fa-solid fa-file-alt"></i> Document Summarizer</a></li>
            <li><a href="<?= url('ai-hub/resume-analyzer') ?>"><i class="fa-solid fa-id-card"></i> Resume Analyzer</a></li>
            <li><a href="<?= url('ai-hub/code-assistant') ?>"><i class="fa-solid fa-code"></i> Code Assistant</a></li>
            <li><a href="<?= url('ai-hub/meeting-notes') ?>"><i class="fa-solid fa-clipboard-list"></i> Meeting Notes</a></li>
            <li><a href="<?= url('ai-hub') ?>" style="color:var(--kw-primary);font-weight:700;"><i class="fa-solid fa-stars"></i> All AI Tools →</a></li>
          </ul>
        </li>

        <!-- Innovation Lab -->
        <li>
          <a href="<?= url('innovation-lab') ?>" class="nav-link <?= isActiveSection('innovation-lab') ?>">
            <i class="fa-solid fa-flask" style="font-size:0.75rem;color:var(--kw-primary);margin-right:0.2rem;"></i>Lab
          </a>
        </li>

        <!-- Community -->
        <li>
          <a href="<?= url('community') ?>" class="nav-link <?= isActiveSection('community') ?>">Community</a>
        </li>

        <!-- Pricing -->
        <li>
          <a href="<?= url('pricing') ?>" class="nav-link <?= isActivePage('pricing') ?>">Pricing</a>
        </li>

        <!-- More Dropdown -->
        <li class="nav-dropdown">
          <a href="#" class="nav-link">More</a>
          <ul class="nav-dropdown-menu" role="list">
            <li><a href="<?= url('demo') ?>"><i class="fa-solid fa-play-circle"></i> Demo Center</a></li>
            <li><a href="<?= url('consultation') ?>"><i class="fa-solid fa-calendar-check"></i> Book Consultation</a></li>
            <li><a href="<?= url('blog') ?>"><i class="fa-solid fa-newspaper"></i> Blog & Insights</a></li>
            <li><a href="<?= url('about') ?>"><i class="fa-solid fa-info-circle"></i> About Us</a></li>
            <li><a href="<?= url('careers') ?>"><i class="fa-solid fa-briefcase"></i> Careers</a></li>
            <li><a href="<?= url('contact') ?>"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
            <li><a href="<?= url('faq') ?>"><i class="fa-solid fa-question-circle"></i> FAQ</a></li>
            <li><a href="<?= url('privacy') ?>"><i class="fa-solid fa-shield-alt"></i> Privacy Policy</a></li>
            <li><a href="<?= url('terms') ?>"><i class="fa-solid fa-file-contract"></i> Terms & Conditions</a></li>
          </ul>
        </li>

      </ul>

      <!-- Right Actions -->
      <div class="nav-actions">
        <button id="theme-toggle" aria-label="Toggle theme" title="Toggle theme">
          <i class="fa-solid fa-moon"></i>
        </button>

        <?php if (isLoggedIn()): ?>
          <a href="<?= url('portal') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">
            <i class="fa-solid fa-user-circle"></i>
            <?= e(currentUser()['name']) ?>
          </a>
        <?php else: ?>
          <a href="<?= url('portal/login') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">
            <i class="fa-solid fa-sign-in-alt"></i> Portal
          </a>
        <?php endif; ?>

        <a href="<?= url('demo') ?>" class="kw-btn kw-btn-primary kw-btn-sm">
          <i class="fa-solid fa-rocket"></i> Request Demo
        </a>

        <!-- Mobile toggle -->
        <button id="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>

    </div>
  </div>
</nav>

<!-- Mobile Navigation Overlay -->
<div id="mobile-nav" role="dialog" aria-modal="true" aria-label="Mobile navigation">
  <div class="mobile-nav-header">
    <a href="<?= url() ?>" class="nav-logo">
      <img src="<?= asset('img/logo.png') ?>" alt="Logo" width="34" height="34">
      <span class="nav-logo-text">Krest<span>works</span></span>
    </a>
    <button id="mobile-nav-close" aria-label="Close menu">
      <i class="fa-solid fa-times" style="font-size:1.2rem;color:var(--kw-text-muted);"></i>
    </button>
  </div>

  <div class="mobile-nav-links">

    <a href="<?= url() ?>" class="mobile-nav-link <?= isActivePage('') ?>">
      <i class="fa-solid fa-house"></i> Home
    </a>

    <!-- Services group -->
    <div class="mobile-nav-group">
      <button class="mobile-nav-link mobile-nav-group-trigger" style="width:100%;background:none;border:none;text-align:left;">
        <i class="fa-solid fa-cogs"></i> Services
        <i class="fa-solid fa-chevron-down" style="margin-left:auto;font-size:0.7rem;color:var(--kw-text-muted);"></i>
      </button>
      <div class="mobile-nav-sub">
        <a href="<?= url('services/custom-software') ?>"><i class="fa-solid fa-laptop-code"></i> Custom Software</a>
        <a href="<?= url('services/web-development') ?>"><i class="fa-solid fa-globe"></i> Web Development</a>
        <a href="<?= url('services/mobile-development') ?>"><i class="fa-solid fa-mobile-alt"></i> Mobile Development</a>
        <a href="<?= url('services/ai-solutions') ?>"><i class="fa-solid fa-robot"></i> AI Solutions</a>
        <a href="<?= url('services/consulting') ?>"><i class="fa-solid fa-lightbulb"></i> Consulting</a>
        <a href="<?= url('services/cloud-infrastructure') ?>"><i class="fa-solid fa-cloud"></i> Cloud</a>
        <a href="<?= url('services/cybersecurity') ?>"><i class="fa-solid fa-shield-halved"></i> Cybersecurity</a>
        <a href="<?= url('services') ?>" style="color:var(--kw-primary);">View All Services →</a>
      </div>
    </div>

    <!-- Products group -->
    <div class="mobile-nav-group">
      <button class="mobile-nav-link mobile-nav-group-trigger" style="width:100%;background:none;border:none;text-align:left;">
        <i class="fa-solid fa-boxes-stacked"></i> Products
        <i class="fa-solid fa-chevron-down" style="margin-left:auto;font-size:0.7rem;color:var(--kw-text-muted);"></i>
      </button>
      <div class="mobile-nav-sub">
        <a href="<?= url('products/hr-system') ?>"><i class="fa-solid fa-users"></i> HR System</a>
        <a href="<?= url('products/procurement-system') ?>"><i class="fa-solid fa-shopping-cart"></i> Procurement</a>
        <a href="<?= url('products/elearning-system') ?>"><i class="fa-solid fa-graduation-cap"></i> eLearning</a>
        <a href="<?= url('products/real-estate-system') ?>"><i class="fa-solid fa-building"></i> Real Estate</a>
        <a href="<?= url('products/supply-chain-system') ?>"><i class="fa-solid fa-truck"></i> Supply Chain</a>
        <a href="<?= url('products/crm-system') ?>"><i class="fa-solid fa-handshake"></i> CRM</a>
        <a href="<?= url('products') ?>" style="color:var(--kw-primary);">View All Products →</a>
      </div>
    </div>

    <a href="<?= url('ai-hub') ?>" class="mobile-nav-link <?= isActiveSection('ai-hub') ?>">
      <i class="fa-solid fa-robot"></i> AI Hub
    </a>

    <a href="<?= url('innovation-lab') ?>" class="mobile-nav-link <?= isActiveSection('innovation-lab') ?>">
      <i class="fa-solid fa-flask"></i> Innovation Lab
    </a>

    <a href="<?= url('community') ?>" class="mobile-nav-link <?= isActiveSection('community') ?>">
      <i class="fa-solid fa-people-group"></i> Community
    </a>

    <a href="<?= url('pricing') ?>" class="mobile-nav-link <?= isActivePage('pricing') ?>">
      <i class="fa-solid fa-tags"></i> Pricing
    </a>

    <a href="<?= url('demo') ?>" class="mobile-nav-link <?= isActivePage('demo') ?>">
      <i class="fa-solid fa-play-circle"></i> Demo Center
    </a>

    <a href="<?= url('blog') ?>" class="mobile-nav-link <?= isActivePage('blog') ?>">
      <i class="fa-solid fa-newspaper"></i> Blog
    </a>

    <a href="<?= url('contact') ?>" class="mobile-nav-link <?= isActivePage('contact') ?>">
      <i class="fa-solid fa-envelope"></i> Contact
    </a>

    <div style="margin-top:1.5rem;display:flex;flex-direction:column;gap:0.6rem;padding-top:1rem;border-top:1px solid var(--kw-border);">
      <a href="<?= url('portal/login') ?>" class="kw-btn kw-btn-ghost" style="width:100%;justify-content:center;">
        <i class="fa-solid fa-sign-in-alt"></i> Client Portal
      </a>
      <a href="<?= url('demo') ?>" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;">
        <i class="fa-solid fa-rocket"></i> Request Demo
      </a>
    </div>
  </div>
</div>