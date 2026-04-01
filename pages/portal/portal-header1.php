<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/auth.php';

//session_start_safe();
if (!empty($_SESSION['user_id'])) { redirect('portal'); }

$page_title = 'Create Account — ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="en" id="kw-html">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= e($page_title) ?></title>
  <meta name="robots" content="noindex">
  <meta name="csrf-token" content="<?= csrfGenerate() ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/krest.css') ?>">
  <style>
    body { min-height:100vh; display:flex; align-items:center; justify-content:center; background:var(--kw-bg); padding:2rem 1rem; }
  </style>
</head>
<body>
<div style="width:100%;max-width:520px;">
  <div style="text-align:center;margin-bottom:2rem;">
    <a href="<?= url() ?>" style="display:inline-block;margin-bottom:1.25rem;">
      <div style="width:52px;height:52px;border-radius:12px;background:var(--kw-primary);display:flex;align-items:center;justify-content:center;margin:0 auto;">
        <span style="font-family:'Poppins',sans-serif;font-weight:900;font-size:1.1rem;color:#0A0F1A;">KC</span>
      </div>
    </a>
    <h1 style="font-size:1.4rem;margin-bottom:0.3rem;">Create Client Account</h1>
    <p style="font-size:0.82rem;color:var(--kw-text-muted);">Access your systems, downloads, and support after registration.</p>
  </div>

  <div class="kw-card" style="padding:2rem;">
    <form id="register-form" novalidate>
      <?= csrfField() ?>
      <input type="text" name="website" style="display:none;" autocomplete="off">

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
        <div class="kw-form-group">
          <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
          <input type="text" name="name" class="kw-input" placeholder="Jane Muthoni" autocomplete="name">
          <div class="kw-field-error"></div>
        </div>
        <div class="kw-form-group">
          <label class="kw-label">Work Email <span style="color:#EF4444;">*</span></label>
          <input type="email" name="email" class="kw-input" placeholder="jane@company.co.ke" autocomplete="email">
          <div class="kw-field-error"></div>
        </div>
        <div class="kw-form-group">
          <label class="kw-label">Company</label>
          <input type="text" name="company" class="kw-input" placeholder="Company name" autocomplete="organization">
        </div>
        <div class="kw-form-group">
          <label class="kw-label">Phone Number</label>
          <input type="tel" name="phone" class="kw-input" placeholder="+254 700 000 000" autocomplete="tel">
        </div>
        <div class="kw-form-group">
          <label class="kw-label">Password <span style="color:#EF4444;">*</span></label>
          <div style="position:relative;">
            <input type="password" name="password" id="reg-pw" class="kw-input" style="padding-right:2.5rem;" placeholder="Min 8 chars, 1 uppercase, 1 number" autocomplete="new-password" oninput="checkStrength(this.value)">
            <button type="button" onclick="togglePwd('reg-pw',this)" style="position:absolute;right:0.65rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--kw-text-muted);cursor:pointer;">
              <i class="fa-solid fa-eye" style="font-size:0.75rem;"></i>
            </button>
          </div>
          <div id="pw-strength" style="height:3px;border-radius:2px;background:var(--kw-border);margin-top:0.35rem;overflow:hidden;">
            <div id="pw-bar" style="height:100%;width:0%;border-radius:2px;transition:width 0.3s,background 0.3s;"></div>
          </div>
          <div class="kw-field-error"></div>
        </div>
        <div class="kw-form-group">
          <label class="kw-label">Confirm Password <span style="color:#EF4444;">*</span></label>
          <div style="position:relative;">
            <input type="password" name="password_confirm" id="reg-pw2" class="kw-input" style="padding-right:2.5rem;" placeholder="Repeat password" autocomplete="new-password">
            <button type="button" onclick="togglePwd('reg-pw2',this)" style="position:absolute;right:0.65rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--kw-text-muted);cursor:pointer;">
              <i class="fa-solid fa-eye" style="font-size:0.75rem;"></i>
            </button>
          </div>
          <div class="kw-field-error"></div>
        </div>
      </div>

      <label style="display:flex;align-items:flex-start;gap:0.5rem;cursor:pointer;font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">
        <input type="checkbox" name="agree_terms" required style="accent-color:var(--kw-primary);margin-top:2px;flex-shrink:0;">
        <span>I agree to the <a href="<?= url('terms') ?>" target="_blank" style="color:var(--kw-primary);">Terms & Conditions</a> and <a href="<?= url('privacy') ?>" target="_blank" style="color:var(--kw-primary);">Privacy Policy</a>.</span>
      </label>

      <div id="register-alert"></div>

      <button type="submit" id="reg-btn" class="kw-btn kw-btn-primary kw-btn-lg" style="width:100%;justify-content:center;">
        <i class="fa-solid fa-user-plus"></i> Create Account
      </button>

      <p style="text-align:center;margin-top:1.25rem;font-size:0.8rem;color:var(--kw-text-muted);">
        Already have an account? <a href="<?= url('portal/login') ?>" style="color:var(--kw-primary);font-weight:600;">Sign in</a>
      </p>
    </form>
  </div>
  <div style="text-align:center;margin-top:1.25rem;">
    <a href="<?= url() ?>" style="font-size:0.75rem;color:var(--kw-text-muted);text-decoration:none;">
      <i class="fa-solid fa-arrow-left" style="margin-right:0.3rem;"></i>Back to <?= APP_NAME ?>
    </a>
  </div>
</div>

<script src="<?= asset('js/krest-theme.js') ?>"></script>
<script>
function togglePwd(id, btn) {
  const i = document.getElementById(id);
  i.type = i.type === 'password' ? 'text' : 'password';
  btn.querySelector('i').className = i.type === 'text' ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
}
function checkStrength(pw) {
  const bar = document.getElementById('pw-bar');
  let score = 0;
  if (pw.length >= 8) score++;
  if (/[A-Z]/.test(pw)) score++;
  if (/[0-9]/.test(pw)) score++;
  if (/[^A-Za-z0-9]/.test(pw)) score++;
  const colors = ['#EF4444','#F97316','#F5A800','#22C55E'];
  bar.style.width = (score * 25) + '%';
  bar.style.background = colors[score - 1] || '';
}
document.getElementById('register-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('reg-btn');
  document.querySelectorAll('.kw-field-error').forEach(el => el.textContent = '');
  document.getElementById('register-alert').innerHTML = '';
  if (!this.querySelector('[name="agree_terms"]').checked) {
    document.getElementById('register-alert').innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Please agree to the Terms & Conditions.</div>';
    return;
  }
  btn.disabled = true;
  btn.innerHTML = '<span style="display:inline-block;width:14px;height:14px;border:2px solid #0A0F1A33;border-top-color:#0A0F1A;border-radius:50%;animation:spin 0.6s linear infinite;vertical-align:middle;margin-right:6px;"></span>Creating account…';
  try {
    const resp = await fetch('<?= url('api/auth-register') ?>', {
      method:'POST',
      headers:{'X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
      body:new FormData(this)
    });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('register-alert').innerHTML = '<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">Account created! Redirecting to login…</div>';
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Account Created!';
      btn.style.background = '#22C55E';
      setTimeout(() => window.location.href = '<?= url('portal/login') ?>', 1500);
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v]) => {
        const el = this.querySelector(`[name="${k}"]`);
        if(el){const err=el.closest('.kw-form-group')?.querySelector('.kw-field-error');if(err)err.textContent=v;}
      });
      document.getElementById('register-alert').innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
      btn.disabled=false;
      btn.innerHTML='<i class="fa-solid fa-user-plus"></i> Create Account';
    }
  } catch {
    document.getElementById('register-alert').innerHTML='<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error. Please try again.</div>';
    btn.disabled=false;
    btn.innerHTML='<i class="fa-solid fa-user-plus"></i> Create Account';
  }
});
</script>
<style>
@keyframes spin{to{transform:rotate(360deg)}}
@media(max-width:500px){.kw-card form > div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}}
</style>
</body>
</html>