<?php /** @var string $name, $subject, $message */ ?>
<h2>We've received your message, <?= e($name) ?>!</h2>
<p>Thank you for reaching out to <strong><?= APP_NAME ?></strong>. Our team has received your inquiry and will respond within <strong>24 hours</strong>.</p>

<div style="background:#f9fafb;border-radius:10px;padding:24px;margin:20px 0;border:1px solid #e5e7eb;">
  <div class="info-row">
    <span class="info-label">Subject</span>
    <span class="info-value"><?= e($subject) ?></span>
  </div>
  <div class="info-row" style="border:none;">
    <span class="info-label">Message</span>
    <span class="info-value" style="font-style:italic;color:#6B7280;"><?= $message ?></span>
  </div>
</div>

<div class="alert-box">
  <p>💡 While you wait, explore our <a href="<?= url('products') ?>" style="color:#C47F00;">enterprise products</a> or try our <a href="<?= url('ai-hub') ?>" style="color:#C47F00;">free AI tools</a>.</p>
</div>

<p>Need immediate assistance? You can reach us on:</p>
<p>
  📧 <a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a><br>
  📱 <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>"><?= WHATSAPP_DISPLAY ?></a>
</p>

<a href="<?= url('contact') ?>" class="kw-btn-email">Visit Our Website</a>