<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

session_name(SESSION_NAME);
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    redirect('admin');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    csrfAbortIfInvalid();

    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        exit;
    }

    $result = loginUser($email, $password);

    if (!$result['success'] || $result['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Invalid admin credentials.']);
        exit;
    }

    echo json_encode(['success' => true, 'redirect' => url('admin')]);
    exit;
}

$csrf_field = csrfField();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — <?= APP_NAME ?></title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/krest.css') ?>">
  <style>
    body { background: #060b14; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; }
  </style>
</head>
<body>
<div style="width:100%;max-width:380px;">
  <div style="text-align:center;margin-bottom:1.75rem;">
    <div style="width:52px;height:52px;background:#F5A800;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 0.85rem;font-size:1.2rem;font-weight:800;color:#0A0F1A;font-family:'Poppins',sans-serif;">KC</div>
    <h1 style="color:#fff;font-size:1.25rem;margin:0 0 0.25rem;">Admin Access</h1>
    <p style="color:rgba(255,255,255,0.35);font-size:0.78rem;"><?= APP_NAME ?></p>
  </div>
  <div style="background:#111827;border:1px solid #1F2937;border-radius:14px;padding:1.75rem;">
    <form id="admin-login-form">
      <?= $csrf_field ?>
      <div style="margin-bottom:1rem;">
        <label style="display:block;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:rgba(255,255,255,0.4);margin-bottom:0.35rem;">Email</label>
        <input type="email" name="email" required autocomplete="email" autofocus
          style="width:100%;padding:0.65rem 0.9rem;background:#1F2937;border:1px solid #374151;border-radius:8px;font-size:0.875rem;color:#fff;outline:none;box-sizing:border-box;"
          onfocus="this.style.borderColor='#F5A800'" onblur="this.style.borderColor='#374151'"
          placeholder="admin@krestworks.com">
      </div>
      <div style="margin-bottom:1.25rem;">
        <label style="display:block;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:rgba(255,255,255,0.4);margin-bottom:0.35rem;">Password</label>
        <input type="password" name="password" required autocomplete="current-password"
          style="width:100%;padding:0.65rem 0.9rem;background:#1F2937;border:1px solid #374151;border-radius:8px;font-size:0.875rem;color:#fff;outline:none;box-sizing:border-box;"
          onfocus="this.style.borderColor='#F5A800'" onblur="this.style.borderColor='#374151'"
          placeholder="••••••••">
      </div>
      <div id="login-error" style="display:none;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:0.65rem 0.85rem;font-size:0.78rem;color:#EF4444;margin-bottom:1rem;"></div>
      <button type="submit" id="login-btn" style="width:100%;padding:0.7rem;background:#F5A800;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;color:#0A0F1A;cursor:pointer;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
        <i class="fa-solid fa-shield-halved"></i> Sign In to Admin
      </button>
    </form>
  </div>
  <div style="text-align:center;margin-top:1.25rem;">
    <a href="<?= url() ?>" style="font-size:0.72rem;color:rgba(255,255,255,0.2);text-decoration:none;">← Back to website</a>
  </div>
</div>
<script>
document.getElementById('admin-login-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('login-btn');
  const err = document.getElementById('login-error');
  err.style.display = 'none';
  btn.disabled = true; btn.textContent = 'Signing in…';
  try {
    const resp = await fetch(window.location.pathname, { method:'POST', body: new FormData(this) });
    const data = await resp.json();
    if (data.success) { window.location.href = data.redirect || '<?= url('admin') ?>'; return; }
    err.textContent = data.message || 'Login failed.';
    err.style.display = 'block';
  } catch(ex) {
    err.textContent = 'Connection error. Please try again.';
    err.style.display = 'block';
  }
  btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-shield-halved"></i> Sign In to Admin';
});
</script>
</body>
</html>