<?php
/**
 * @var string $type, $name, $email, $phone, $company, $subject
 * @var string $message, $id, $time
 * @var string $preferred_date, $preferred_time, $budget, $team_size (optional)
 */
?>
<h2 style="color:#EF4444;">🔔 New <?= e($type) ?></h2>
<p>A new inquiry has been submitted on <strong><?= APP_NAME ?></strong>. Details below:</p>

<div style="background:#f9fafb;border-radius:10px;padding:24px;margin:20px 0;border:1px solid #e5e7eb;">
  <div class="info-row">
    <span class="info-label">Type</span>
    <span class="info-value"><strong style="color:#F5A800;"><?= e($type) ?></strong></span>
  </div>
  <div class="info-row">
    <span class="info-label">Inquiry ID</span>
    <span class="info-value">#<?= e($id) ?></span>
  </div>
  <div class="info-row">
    <span class="info-label">Received</span>
    <span class="info-value"><?= e($time) ?></span>
  </div>
  <div class="info-row">
    <span class="info-label">Name</span>
    <span class="info-value"><strong><?= e($name) ?></strong></span>
  </div>
  <div class="info-row">
    <span class="info-label">Email</span>
    <span class="info-value"><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></span>
  </div>
  <?php if (!empty($phone)): ?>
  <div class="info-row">
    <span class="info-label">Phone</span>
    <span class="info-value"><a href="tel:<?= e($phone) ?>"><?= e($phone) ?></a></span>
  </div>
  <?php endif; ?>
  <?php if (!empty($company)): ?>
  <div class="info-row">
    <span class="info-label">Company</span>
    <span class="info-value"><?= e($company) ?></span>
  </div>
  <?php endif; ?>
  <div class="info-row">
    <span class="info-label">Subject</span>
    <span class="info-value"><?= e($subject) ?></span>
  </div>
  <?php if (!empty($preferred_date)): ?>
  <div class="info-row">
    <span class="info-label">Preferred Date</span>
    <span class="info-value"><?= e($preferred_date) ?></span>
  </div>
  <?php endif; ?>
  <?php if (!empty($preferred_time)): ?>
  <div class="info-row">
    <span class="info-label">Preferred Time</span>
    <span class="info-value"><?= e($preferred_time) ?></span>
  </div>
  <?php endif; ?>
  <?php if (!empty($budget)): ?>
  <div class="info-row">
    <span class="info-label">Budget</span>
    <span class="info-value"><?= e($budget) ?></span>
  </div>
  <?php endif; ?>
  <?php if (!empty($team_size)): ?>
  <div class="info-row">
    <span class="info-label">Team Size</span>
    <span class="info-value"><?= e($team_size) ?></span>
  </div>
  <?php endif; ?>
  <div class="info-row" style="border:none;flex-direction:column;gap:6px;">
    <span class="info-label">Message</span>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px;font-size:14px;color:#374151;line-height:1.75;margin-top:6px;"><?= $message ?></div>
  </div>
</div>

<div style="display:flex;gap:12px;flex-wrap:wrap;">
  <a href="<?= url('admin/inquiries') ?>" class="kw-btn-email">View in Admin Panel</a>
  <a href="mailto:<?= e($email) ?>" style="display:inline-block;background:#f3f4f6;color:#0A0F1A;padding:13px 24px;border-radius:999px;font-weight:700;font-size:14px;text-decoration:none;">Reply by Email</a>
  <?php if (!empty($phone)): ?>
  <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $phone) ?>" style="display:inline-block;background:#25D366;color:#fff;padding:13px 24px;border-radius:999px;font-weight:700;font-size:14px;text-decoration:none;">WhatsApp</a>
  <?php endif; ?>
</div>