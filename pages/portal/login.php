<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/auth.php';

//session_start();

// Already logged in → redirect
if (!empty($_SESSION['user_id'])) {
    redirect($_SESSION['user_role'] === 'admin' ? 'admin' : 'portal');
}

$page_title = 'Client Login — ' . APP_NAME;
$redirect   = $_GET['redirect'] ?? '';
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
    .auth-split { display:grid; grid-template-columns:1fr 1fr; min-height:520px; max-width:900px; width:100%; border-radius:var(--kw-radius-xl); overflow:hidden; box-shadow:0 24px 64px rgba(0,0,0,0.3); }
    .auth-panel { padding:3rem 2.5rem; }
    .auth-brand { background:var(--kw-bg-hero); display:flex; flex-direction:column; justify-content:center; position:relative; overflow:hidden; }
    .auth-brand::before { content:''; position:absolute; top:-40%; right:-20%; width:400px; height:400px; border-radius:50%; background:rgba(245,168,0,0.08); pointer-events:none; }
    .auth-brand::after { content:''; position:absolute; bottom:-30%; left:-10%; width:280px; height:280px; border-radius:50%; background:rgba(245,168,0,0.05); pointer-events:none; }
    .auth-form { background:var(--kw-bg-card); display:flex; flex-direction:column; justify-content:center; }
    @media(max-width:700px) {
      .auth-split { grid-template-columns:1fr; box-shadow:none; background:var(--kw-bg-card); border-radius:var(--kw-radius-xl); }
      .auth-brand { display:none; }
      .auth-panel { padding:2rem; }
    }
  </style>
</head>
<body>
<div class="auth-split">

  <!-- Brand side -->
  <div class="auth-panel auth-brand">
    <div style="position:relative;z-index:1;">
      <div style="width:60px;height:60px;border-radius:14px;background:var(--kw-primary);display:flex;align-items:center;justify-content:center;margin-bottom:2rem;">
        <span style="font-family:'Poppins',sans-serif;font-weight:900;font-size:1.25rem;color:#0A0F1A;">KC</span>
      </div>
      <h1 style="color:#fff;font-size:1.75rem;margin-bottom:0.75rem;line-height:1.2;"><?= APP_NAME ?><br><span style="color:var(--kw-primary);">Client Portal</span></h1>
      <p style="color:rgba(255,255,255,0.55);font-size:0.875rem;line-height:1.7;margin-bottom:2.5rem;">Your central hub for managing subscriptions, accessing systems, training resources, and support.</p>
      <div style="display:flex;flex-direction:column;gap:0.6rem;">
        <?php foreach ([
          ['fa-layer-group','Manage your system subscriptions'],
          ['fa-download','Download products and resources'],
          ['fa-graduation-cap','Access training and documentation'],
          ['fa-headset','Raise and track support tickets'],
        ] as $f): ?>
        <div style="display:flex;align-items:center;gap:0.65rem;font-size:0.8rem;color:rgba(255,255,255,0.6);">
          <i class="fa-solid <?= $f[0] ?>" style="color:var(--kw-primary);width:16px;font-size:0.75rem;flex-shrink:0;"></i><?= $f[1] ?>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:2.5rem;padding-top:2rem;border-top:1px solid rgba(255,255,255,0.1);font-size:0.75rem;color:rgba(255,255,255,0.3);">
        Not a client yet? <a href="<?= url('demo') ?>" style="color:var(--kw-primary);">Request a demo</a>
      </div>
    </div>
  </div>

  <!-- Form side -->
  <div class="auth-panel auth-form">
    <div style="margin-bottom:2rem;">
      <h2 style="font-size:1.35rem;margin-bottom:0.3rem;">Sign In</h2>
      <p style="font-size:0.82rem;color:var(--kw-text-muted);">Welcome back — enter your credentials to continue.</p>
    </div>

    <form id="login-form" novalidate>
      <?= csrfField() ?>
      <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
      <input type="text" name="website" style="display:none;" autocomplete="off">

      <div class="kw-form-group" style="margin-bottom:1rem;">
        <label class="kw-label">Email Address</label>
        <div style="position:relative;">
          <i class="fa-solid fa-envelope" style="position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);color:var(--kw-text-muted);font-size:0.8rem;pointer-events:none;"></i>
          <input type="email" name="email" class="kw-input" style="padding-left:2.5rem;" placeholder="you@company.com" autocomplete="email" autofocus>
        </div>
        <div class="kw-field-error"></div>
      </div>

      <div class="kw-form-group" style="margin-bottom:0.75rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
          <label class="kw-label" style="margin-bottom:0;">Password</label>
          <a href="<?= url('portal/forgot-password') ?>" style="font-size:0.75rem;color:var(--kw-primary);text-decoration:none;">Forgot password?</a>
        </div>
        <div style="position:relative;">
          <i class="fa-solid fa-lock" style="position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);color:var(--kw-text-muted);font-size:0.8rem;pointer-events:none;"></i>
          <input type="password" name="password" id="login-pw" class="kw-input" style="padding-left:2.5rem;padding-right:2.5rem;" placeholder="••••••••" autocomplete="current-password">
          <button type="button" onclick="togglePwd('login-pw',this)" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--kw-text-muted);cursor:pointer;">
            <i class="fa-solid fa-eye" style="font-size:0.78rem;"></i>
          </button>
        </div>
        <div class="kw-field-error"></div>
      </div>

      <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.8rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">
        <input type="checkbox" name="remember" style="accent-color:var(--kw-primary);"> Keep me signed in for 7 days
      </label>

      <div id="login-alert"></div>

      <button type="submit" id="login-btn" class="kw-btn kw-btn-primary kw-btn-lg" style="width:100%;justify-content:center;">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
      </button>

      <div style="display:flex;align-items:center;gap:1rem;margin:1.25rem 0;">
        <div style="flex:1;height:1px;background:var(--kw-border);"></div>
        <span style="font-size:0.72rem;color:var(--kw-text-muted);">OR</span>
        <div style="flex:1;height:1px;background:var(--kw-border);"></div>
      </div>

      <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi%2C+I+need+portal+access" target="_blank" class="kw-btn kw-btn-lg" style="width:100%;justify-content:center;background:#25D366;color:#fff;border:none;">
        <i class="fa-brands fa-whatsapp"></i> Sign In via WhatsApp Support
      </a>

      <p style="text-align:center;margin-top:1.25rem;font-size:0.8rem;color:var(--kw-text-muted);">
        Don't have an account? <a href="<?= url('portal/register') ?>" style="color:var(--kw-primary);font-weight:600;">Create one</a>
      </p>
      <p style="text-align:center;margin-top:0.5rem;">
        <a href="<?= url() ?>" style="font-size:0.75rem;color:var(--kw-text-muted);text-decoration:none;">
          <i class="fa-solid fa-arrow-left" style="margin-right:0.3rem;"></i>Back to <?= APP_NAME ?>
        </a>
      </p>
    </form>
  </div>
</div>

<script src="<?= asset('js/krest-theme.js') ?>"></script>
<script>
function togglePwd(id, btn) {
  const i = document.getElementById(id);
  i.type = i.type === 'password' ? 'text' : 'password';
  btn.querySelector('i').className = i.type === 'text' ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
  btn.querySelector('i').style.fontSize = '0.78rem';
}
document.getElementById('login-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('login-btn');
  document.querySelectorAll('.kw-field-error').forEach(el => el.textContent = '');
  document.getElementById('login-alert').innerHTML = '';
  btn.disabled = true;
  btn.innerHTML = '<span style="display:inline-block;width:14px;height:14px;border:2px solid #0A0F1A33;border-top-color:#0A0F1A;border-radius:50%;animation:spin 0.6s linear infinite;vertical-align:middle;margin-right:6px;"></span>Signing in…';
  try {
    const resp = await fetch('<?= url('api/auth-login') ?>', {
      method:'POST',
      headers:{'X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
      body:new FormData(this)
    });
    const data = await resp.json();
    if (data.success) {
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Signed in!';
      btn.style.background='#22C55E';
      setTimeout(() => window.location.href = data.redirect || '<?= url('portal') ?>', 700);
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v]) => {
        const el = this.querySelector(`[name="${k}"]`);
        if(el){const err=el.closest('.kw-form-group')?.querySelector('.kw-field-error');if(err)err.textContent=v;}
      });
      document.getElementById('login-alert').innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
      btn.disabled=false;
      btn.innerHTML='<i class="fa-solid fa-right-to-bracket"></i> Sign In';
    }
  } catch {
    document.getElementById('login-alert').innerHTML='<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Connection error. Please try again.</div>';
    btn.disabled=false;
    btn.innerHTML='<i class="fa-solid fa-right-to-bracket"></i> Sign In';
  }
});
</script>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
</body>
</html>