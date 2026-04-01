<?php
$page_title = 'Application Submitted — ' . APP_NAME;
require_once __DIR__ . '/../../includes/header.php';
$ref = e($_GET['ref'] ?? '');
?>
<section style="min-height:60vh;display:flex;align-items:center;background:var(--kw-bg);padding:4rem 0;">
  <div class="kw-container" style="max-width:640px;text-align:center;">
    <div style="width:80px;height:80px;border-radius:50%;background:rgba(34,197,94,0.15);border:3px solid #22C55E;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;" data-aos="zoom-in">
      <i class="fa-solid fa-check" style="font-size:2rem;color:#22C55E;"></i>
    </div>
    <h1 style="font-size:1.75rem;margin-bottom:0.75rem;" data-aos="fade-up">Application Submitted!</h1>
    <p style="font-size:0.95rem;color:var(--kw-text-muted);margin-bottom:1.5rem;" data-aos="fade-up" data-aos-delay="50">
      Thank you for applying to <?= APP_NAME ?>. We've received your application and will review it carefully.
    </p>
    <?php if ($ref): ?>
    <div style="background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-xl);padding:1.5rem;margin-bottom:1.75rem;display:inline-block;" data-aos="fade-up" data-aos-delay="100">
      <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--kw-text-muted);margin-bottom:0.4rem;">Your Application Reference</div>
      <div style="font-size:1.5rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);letter-spacing:0.05em;"><?= $ref ?></div>
      <div style="font-size:0.72rem;color:var(--kw-text-muted);margin-top:0.3rem;">Save this number for tracking your application</div>
    </div>
    <?php endif; ?>
    <div style="background:rgba(245,168,0,0.06);border:1px solid rgba(245,168,0,0.15);border-radius:var(--kw-radius-xl);padding:1.25rem;margin-bottom:2rem;text-align:left;" data-aos="fade-up" data-aos-delay="150">
      <h4 style="font-size:0.875rem;margin-bottom:0.75rem;"><i class="fa-solid fa-info-circle" style="color:var(--kw-primary);margin-right:0.4rem;"></i>What Happens Next</h4>
      <?php foreach ([
        ['fa-search','Review','Our recruitment team will review your application (1–2 weeks after deadline).'],
        ['fa-list-check','Shortlisting','Shortlisted candidates will be notified by email and phone.'],
        ['fa-video','Interview','If shortlisted, you\'ll receive a personalised interview invitation.'],
        ['fa-handshake','Offer','Successful candidates will receive a formal offer letter.'],
      ] as $step): ?>
      <div style="display:flex;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.78rem;color:var(--kw-text-secondary);">
        <i class="fa-solid <?= $step[0] ?>" style="color:var(--kw-primary);flex-shrink:0;width:14px;margin-top:2px;font-size:0.72rem;"></i>
        <div><strong><?= $step[1] ?></strong> — <?= $step[2] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;" data-aos="fade-up" data-aos-delay="200">
      <a href="<?= url('careers') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-briefcase"></i> View More Positions</a>
      <a href="<?= url() ?>" class="kw-btn kw-btn-ghost"><i class="fa-solid fa-home"></i> Back to Home</a>
    </div>
    <p style="margin-top:1.5rem;font-size:0.75rem;color:var(--kw-text-muted);">
      Questions? Email <a href="mailto:careers@krestworks.com" style="color:var(--kw-primary);">careers@krestworks.com</a>
    </p>
  </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>