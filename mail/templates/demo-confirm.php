<?php /** @var string $name, $product, $demo_type, $preferred_date, $preferred_time */ ?>
<h2>Demo Request Confirmed!</h2>
<p>Hi <strong><?= e($name) ?></strong>, your demo request has been received. Our team will confirm the schedule and send you a calendar invite.</p>

<div style="background:#f9fafb;border-radius:10px;padding:24px;margin:20px 0;border:1px solid #e5e7eb;">
  <div class="info-row">
    <span class="info-label">Product</span>
    <span class="info-value"><strong><?= e($product) ?></strong></span>
  </div>
  <div class="info-row">
    <span class="info-label">Demo Type</span>
    <span class="info-value"><?= e($demo_type) ?></span>
  </div>
  <div class="info-row">
    <span class="info-label">Preferred Date</span>
    <span class="info-value"><?= e($preferred_date) ?></span>
  </div>
  <div class="info-row" style="border:none;">
    <span class="info-label">Preferred Time</span>
    <span class="info-value"><?= e($preferred_time) ?></span>
  </div>
</div>

<div class="alert-box">
  <p>⚡ Our team will reach out within <strong>24 hours</strong> to confirm your session and send connection details.</p>
</div>

<p>Preparing for your demo? Explore the product documentation and feature highlights here:</p>
<a href="<?= url('products') ?>" class="kw-btn-email">View Product Details</a>

<p style="margin-top:20px;font-size:13px;color:#6B7280;">Questions? WhatsApp us at <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>"><?= WHATSAPP_DISPLAY ?></a></p>