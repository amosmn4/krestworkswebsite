<?php /** @var string $name, $email */ ?>
<h2>Welcome to <?= APP_NAME ?>, <?= e($name) ?>!</h2>
<p>We're thrilled to have you on board. <?= APP_NAME ?> is your gateway to AI-powered enterprise software and a growing community of developers and business leaders.</p>

<div style="background:linear-gradient(135deg,#0A0F1A,#1a253a);border-radius:12px;padding:28px;margin:24px 0;">
  <p style="color:rgba(255,255,255,0.6);font-size:13px;margin-bottom:16px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">What you can do now</p>
  <?php
  $actions = [
    ['🚀', 'Explore our enterprise systems', 'products'],
    ['🤖', 'Try free AI tools in the AI Hub', 'ai-hub'],
    ['🔬', 'Experiment in the Innovation Lab', 'innovation-lab'],
    ['💬', 'Join the Community', 'community'],
    ['📅', 'Book a consultation or demo', 'demo'],
  ];
  foreach ($actions as $a): ?>
  <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
    <span style="font-size:18px;"><?= $a[0] ?></span>
    <a href="<?= url($a[2]) ?>" style="color:#F5A800;font-size:14px;text-decoration:none;font-weight:500;"><?= $a[1] ?></a>
  </div>
  <?php endforeach; ?>
</div>

<div class="alert-box">
  <p>🎯 Did you know? You can talk to <strong>Krest AI</strong> — our AI assistant — on any page of the website. Just click the gold robot icon!</p>
</div>

<p style="margin-top:20px;">Need help getting started? Our team is always here.</p>

<a href="<?= url('portal') ?>" class="kw-btn-email">Go to Your Portal</a>

<p style="margin-top:24px;font-size:13px;color:#6B7280;">
  Questions? Email us at <a href="mailto:<?= COMPANY_EMAIL ?>" style="color:#C47F00;"><?= COMPANY_EMAIL ?></a>
  or WhatsApp <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" style="color:#25D366;"><?= WHATSAPP_DISPLAY ?></a>
</p>