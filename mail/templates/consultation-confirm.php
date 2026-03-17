<?php /** @var string $name, $consultation_type, $preferred_date, $preferred_time, $message */ ?>
<h2>Consultation Request Received!</h2>
<p>Hi <strong><?= e($name) ?></strong>, thank you for requesting a consultation with <strong><?= APP_NAME ?></strong>. We've received your details and will be in touch within 24 hours.</p>

<div style="background:#f9fafb;border-radius:10px;padding:24px;margin:20px 0;border:1px solid #e5e7eb;">
  <div class="info-row">
    <span class="info-label">Consultation</span>
    <span class="info-value"><strong><?= e($consultation_type) ?></strong></span>
  </div>
  <div class="info-row">
    <span class="info-label">Preferred Date</span>
    <span class="info-value"><?= e($preferred_date) ?></span>
  </div>
  <div class="info-row">
    <span class="info-label">Preferred Time</span>
    <span class="info-value"><?= e($preferred_time) ?></span>
  </div>
  <div class="info-row" style="border:none;">
    <span class="info-label">Your Brief</span>
    <span class="info-value" style="font-style:italic;color:#6B7280;"><?= $message ?></span>
  </div>
</div>

<div class="alert-box">
  <p>🎯 Our consultants will review your brief before the session to ensure maximum value for your time.</p>
</div>

<p>In the meantime, you might find these resources useful:</p>
<ul style="margin:12px 0 20px 20px;">
  <li style="margin-bottom:8px;font-size:14px;color:#374151;"><a href="<?= url('blog') ?>" style="color:#C47F00;">Read our blog</a> — insights on digital transformation & AI</li>
  <li style="margin-bottom:8px;font-size:14px;color:#374151;"><a href="<?= url('products') ?>" style="color:#C47F00;">Explore our products</a> — see what's possible</li>
  <li style="font-size:14px;color:#374151;"><a href="<?= url('innovation-lab') ?>" style="color:#C47F00;">Try the Innovation Lab</a> — interactive tools and simulators</li>
</ul>

<a href="<?= url('consultation') ?>" class="kw-btn-email">Back to Consultation Page</a>