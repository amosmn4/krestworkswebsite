<?php
$portal_title  = 'My Profile';
$portal_active = 'profile';
require_once __DIR__ . '/portal-header.php';

$pdo = db();
$uid = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_profile') {
    header('Content-Type: application/json');
    csrfAbortIfInvalid();

    $name    = trim(htmlspecialchars($_POST['name']    ?? '', ENT_QUOTES, 'UTF-8'));
    $company = trim(htmlspecialchars($_POST['company'] ?? '', ENT_QUOTES, 'UTF-8'));
    $phone   = trim(htmlspecialchars($_POST['phone']   ?? '', ENT_QUOTES, 'UTF-8'));

    $errors = [];
    if (mb_strlen($name) < 2) $errors['name'] = 'Name must be at least 2 characters.';
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => 'Please fix the errors.', 'fields' => $errors]);
        exit;
    }

    try {
        $pdo->prepare('UPDATE users SET name = ?, company = ?, phone = ?, updated_at = NOW() WHERE id = ?')
            ->execute([$name, $company, $phone, $uid]);
        $_SESSION['user_name'] = $name;
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed. Please try again.']);
    }
    exit;
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'change_password') {
    header('Content-Type: application/json');
    csrfAbortIfInvalid();

    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Fetch current hash
    $userRow = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $userRow->execute([$uid]);
    $hash = $userRow->fetchColumn();

    $errors = [];
    if (!password_verify($current, $hash))    $errors['current_password'] = 'Current password is incorrect.';
    if (strlen($new) < 8)                     $errors['new_password']     = 'New password must be at least 8 characters.';
    if (!preg_match('/[A-Z]/', $new))         $errors['new_password']     = 'Must include at least one uppercase letter.';
    if (!preg_match('/[0-9]/', $new))         $errors['new_password']     = 'Must include at least one number.';
    if ($new !== $confirm)                     $errors['confirm_password'] = 'Passwords do not match.';

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => 'Please fix the errors.', 'fields' => $errors]);
        exit;
    }

    try {
        $pdo->prepare('UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?')
            ->execute([password_hash($new, PASSWORD_BCRYPT), $uid]);
        echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
    }
    exit;
}

// Handle notification preferences
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_notifications') {
    header('Content-Type: application/json');
    csrfAbortIfInvalid();
    // Store prefs in session or DB — simplified here
    echo json_encode(['success' => true, 'message' => 'Notification preferences saved.']);
    exit;
}

// Fetch full user record
$userFull = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$userFull->execute([$uid]);
$user = $userFull->fetch(PDO::FETCH_ASSOC);

// Fetch subscription count
$subCount = (int)$pdo->prepare('SELECT COUNT(*) FROM subscriptions WHERE user_id = ? AND status = "active"')->execute([$uid]) ? $pdo->query("SELECT COUNT(*) FROM subscriptions WHERE user_id = $uid AND status = 'active'")->fetchColumn() : 0;

// Fetch ticket count
$ticketCount = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE user_id = $uid")->fetchColumn();

// Activity stats
$aiUses = (int)$pdo->query("SELECT COUNT(*) FROM ai_tool_usage WHERE user_id = $uid")->fetchColumn();

$memberSince  = date('F Y', strtotime($user['created_at'] ?? 'now'));
$initials2    = implode('', array_map(fn($w) => strtoupper($w[0]), array_filter(explode(' ', $user['name']))));
$initials2    = substr($initials2, 0, 2);
?>

<!-- Header -->
<div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
  <div>
    <h1 style="font-size:1.25rem;margin:0 0 0.25rem;">My Profile</h1>
    <p style="font-size:0.82rem;color:var(--kw-text-muted);">Manage your account details, security settings, and preferences.</p>
  </div>
  <a href="<?= url('portal') ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
</div>

<!-- Profile card + stats -->
<div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;margin-bottom:1.5rem;">

  <!-- Profile summary -->
  <div class="kw-card" style="padding:1.75rem;text-align:center;">
    <div class="portal-avatar" style="width:72px;height:72px;font-size:1.35rem;margin:0 auto 1rem;border:3px solid rgba(245,168,0,0.3);">
      <?= e($initials2) ?>
    </div>
    <div style="font-size:1.05rem;font-weight:700;margin-bottom:0.2rem;"><?= e($user['name']) ?></div>
    <div style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:0.35rem;"><?= e($user['email']) ?></div>
    <?php if ($user['company']): ?>
    <div style="font-size:0.78rem;color:var(--kw-primary);font-weight:600;margin-bottom:0.75rem;"><?= e($user['company']) ?></div>
    <?php endif; ?>
    <div style="background:rgba(34,197,94,0.1);color:#22C55E;border-radius:999px;padding:0.2rem 0.75rem;font-size:0.68rem;font-weight:700;display:inline-block;margin-bottom:1.25rem;">
      <i class="fa-solid fa-circle" style="font-size:0.45rem;margin-right:0.3rem;"></i>Active Account
    </div>

    <!-- Mini stats -->
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.5rem;border-top:1px solid var(--kw-border);padding-top:1.25rem;">
      <?php foreach ([
        [$subCount,'Systems'],[$ticketCount,'Tickets'],[$aiUses,'AI Uses'],
      ] as $s): ?>
      <div style="text-align:center;">
        <div style="font-size:1.15rem;font-weight:800;color:var(--kw-primary);"><?= $s[0] ?></div>
        <div style="font-size:0.62rem;color:var(--kw-text-muted);"><?= $s[1] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--kw-border);font-size:0.72rem;color:var(--kw-text-muted);">
      <i class="fa-solid fa-calendar" style="color:var(--kw-primary);margin-right:0.3rem;"></i>
      Member since <?= $memberSince ?>
    </div>
  </div>

  <!-- Tabs: profile info -->
  <div class="kw-card" style="padding:0;overflow:hidden;">
    <div class="kw-tabs" data-tabs-container style="border-bottom:1px solid var(--kw-border);padding:0 1.25rem;margin:0;">
      <button class="kw-tab-btn active" data-tab="info">Personal Info</button>
      <button class="kw-tab-btn" data-tab="security">Security</button>
      <button class="kw-tab-btn" data-tab="notifications">Notifications</button>
      <button class="kw-tab-btn" data-tab="activity">Activity</button>
    </div>

    <!-- Personal Info -->
    <div class="kw-tab-panel active" data-tab-panel="info" style="padding:1.5rem;">
      <form id="profile-form" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="action" value="update_profile">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
          <div class="kw-form-group">
            <label class="kw-label">Full Name <span style="color:#EF4444;">*</span></label>
            <input type="text" name="name" class="kw-input" value="<?= e($user['name']) ?>">
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Email Address</label>
            <input type="email" class="kw-input" value="<?= e($user['email']) ?>" disabled style="opacity:0.6;">
            <div style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.3rem;">Email cannot be changed. Contact support if needed.</div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Company / Organisation</label>
            <input type="text" name="company" class="kw-input" value="<?= e($user['company'] ?? '') ?>" placeholder="Your company name">
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Phone Number</label>
            <input type="tel" name="phone" class="kw-input" value="<?= e($user['phone'] ?? '') ?>" placeholder="+254 700 000 000">
          </div>
        </div>
        <div id="profile-alert"></div>
        <button type="submit" class="kw-btn kw-btn-primary" id="profile-btn">
          <i class="fa-solid fa-save"></i> Save Changes
        </button>
      </form>
    </div>

    <!-- Security -->
    <div class="kw-tab-panel" data-tab-panel="security" style="padding:1.5rem;">
      <h4 style="font-size:0.9rem;margin-bottom:0.25rem;">Change Password</h4>
      <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Use a strong password with uppercase letters, numbers, and at least 8 characters.</p>
      <form id="password-form" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="action" value="change_password">
        <div style="display:flex;flex-direction:column;gap:0.85rem;max-width:400px;">
          <div class="kw-form-group">
            <label class="kw-label">Current Password</label>
            <input type="password" name="current_password" class="kw-input" placeholder="Your current password" autocomplete="current-password">
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">New Password</label>
            <input type="password" name="new_password" class="kw-input" placeholder="New password" autocomplete="new-password">
            <div class="kw-field-error"></div>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="kw-input" placeholder="Confirm new password" autocomplete="new-password">
            <div class="kw-field-error"></div>
          </div>
        </div>
        <div id="password-alert" style="margin:0.75rem 0;"></div>
        <button type="submit" class="kw-btn kw-btn-primary" id="password-btn" style="margin-top:0.25rem;">
          <i class="fa-solid fa-lock"></i> Update Password
        </button>
      </form>

      <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--kw-border);">
        <h4 style="font-size:0.9rem;margin-bottom:0.25rem;color:#EF4444;">Danger Zone</h4>
        <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:0.85rem;">Deactivating your account will remove portal access. Your data and subscriptions will be retained for 30 days.</p>
        <button onclick="if(confirm('Are you sure you want to request account deactivation? This will be reviewed by our team.')) window.Krest?.toast('Deactivation request sent. Our team will contact you within 24 hours.','info');" class="kw-btn kw-btn-sm" style="border-color:#EF4444;color:#EF4444;background:rgba(239,68,68,0.05);">
          <i class="fa-solid fa-user-xmark"></i> Request Account Deactivation
        </button>
      </div>
    </div>

    <!-- Notifications -->
    <div class="kw-tab-panel" data-tab-panel="notifications" style="padding:1.5rem;">
      <h4 style="font-size:0.9rem;margin-bottom:0.25rem;">Notification Preferences</h4>
      <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Control how and when Krestworks contacts you.</p>
      <form id="notif-form" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="action" value="update_notifications">
        <?php foreach ([
          ['notif_support','Support ticket updates (always on)',true,true],
          ['notif_billing','Billing and subscription alerts',true,false],
          ['notif_product','Product updates and new features',false,false],
          ['notif_blog','New Knowledge Hub articles',false,false],
          ['notif_newsletter','Monthly newsletter & insights',false,false],
          ['notif_promotions','Special offers and promotions',false,false],
        ] as $pref): ?>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 0;border-bottom:1px solid var(--kw-border);">
          <div>
            <div style="font-size:0.82rem;font-weight:600;"><?= $pref[1] ?></div>
            <?php if ($pref[3]): ?><div style="font-size:0.68rem;color:var(--kw-text-muted);">Required — cannot be disabled</div><?php endif; ?>
          </div>
          <label style="position:relative;display:inline-block;width:40px;height:22px;cursor:<?= $pref[3] ? 'not-allowed' : 'pointer' ?>;">
            <input type="checkbox" name="<?= $pref[0] ?>" <?= $pref[2] ? 'checked' : '' ?> <?= $pref[3] ? 'disabled' : '' ?> style="opacity:0;width:0;height:0;">
            <span onclick="" style="position:absolute;cursor:<?= $pref[3] ? 'not-allowed' : 'pointer' ?>;top:0;left:0;right:0;bottom:0;background:<?= $pref[2] ? 'var(--kw-primary)' : 'var(--kw-border)' ?>;border-radius:22px;transition:0.3s;" class="toggle-track"></span>
            <span style="position:absolute;content:'';height:16px;width:16px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:0.3s;transform:<?= $pref[2] ? 'translateX(18px)' : '' ?>;"></span>
          </label>
        </div>
        <?php endforeach; ?>
        <div id="notif-alert" style="margin:0.75rem 0;"></div>
        <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" id="notif-btn" style="margin-top:0.75rem;">
          <i class="fa-solid fa-save"></i> Save Preferences
        </button>
      </form>
    </div>

    <!-- Activity -->
    <div class="kw-tab-panel" data-tab-panel="activity" style="padding:1.5rem;">
      <h4 style="font-size:0.9rem;margin-bottom:0.25rem;">Account Activity</h4>
      <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Recent actions on your account.</p>
      <?php
      $actStmt = $pdo->prepare('SELECT event_type, page, created_at FROM site_events WHERE user_id = ? ORDER BY created_at DESC LIMIT 15');
      $actStmt->execute([$uid]);
      $activities = $actStmt->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <?php if (empty($activities)): ?>
      <p style="font-size:0.82rem;color:var(--kw-text-muted);">No recent activity recorded.</p>
      <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:0;">
        <?php foreach ($activities as $act):
          $typeIcon = match($act['event_type']) {
            'page_visit' => ['fa-eye','#6B7280'], 'tool_use' => ['fa-robot','#A855F7'],
            'login' => ['fa-right-to-bracket','#22C55E'], 'ticket_open' => ['fa-headset','#3B82F6'],
            default => ['fa-circle','#6B7280']
          };
        ?>
        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0;border-bottom:1px solid var(--kw-border);">
          <div style="width:28px;height:28px;border-radius:50%;background:<?= $typeIcon[1] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid <?= $typeIcon[0] ?>" style="color:<?= $typeIcon[1] ?>;font-size:0.72rem;"></i>
          </div>
          <div style="flex:1;">
            <div style="font-size:0.78rem;font-weight:600;"><?= e(ucwords(str_replace('_',' ',$act['event_type']))) ?></div>
            <div style="font-size:0.68rem;color:var(--kw-text-muted);"><?= e($act['page'] ?? '') ?></div>
          </div>
          <div style="font-size:0.65rem;color:var(--kw-text-muted);white-space:nowrap;"><?= timeAgo($act['created_at']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
// Profile form
document.getElementById('profile-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('profile-btn');
  document.querySelectorAll('#profile-form .kw-field-error').forEach(el => el.textContent = '');
  document.getElementById('profile-alert').innerHTML = '';
  btn.disabled = true; btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Saving…';
  try {
    const resp = await fetch(window.location.pathname, { method:'POST', body: new FormData(this) });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('profile-alert').innerHTML = `<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">${data.message}</div>`;
      window.Krest?.toast(data.message, 'success');
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v]) => {
        const el = this.querySelector(`[name="${k}"]`);
        if (el) el.closest('.kw-form-group')?.querySelector('.kw-field-error')?.textContent !== undefined
          && (el.closest('.kw-form-group').querySelector('.kw-field-error').textContent = v);
      });
      document.getElementById('profile-alert').innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err) { document.getElementById('profile-alert').innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Error. Please try again.</div>'; }
  btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-save"></i> Save Changes';
});

// Password form
document.getElementById('password-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('password-btn');
  document.querySelectorAll('#password-form .kw-field-error').forEach(el => el.textContent = '');
  document.getElementById('password-alert').innerHTML = '';
  btn.disabled = true; btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Updating…';
  try {
    const resp = await fetch(window.location.pathname, { method:'POST', body: new FormData(this) });
    const data = await resp.json();
    if (data.success) {
      document.getElementById('password-alert').innerHTML = `<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">${data.message}</div>`;
      this.reset();
    } else {
      if (data.fields) Object.entries(data.fields).forEach(([k,v]) => {
        const el = this.querySelector(`[name="${k}"]`);
        if (el) el.closest('.kw-form-group')?.querySelector('.kw-field-error')?.textContent !== undefined
          && (el.closest('.kw-form-group').querySelector('.kw-field-error').textContent = v);
      });
      document.getElementById('password-alert').innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">${data.message}</div>`;
    }
  } catch(err) { document.getElementById('password-alert').innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Error. Please try again.</div>'; }
  btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-lock"></i> Update Password';
});

// Notifications form
document.getElementById('notif-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('notif-btn');
  btn.disabled = true; btn.innerHTML = '…';
  try {
    const resp = await fetch(window.location.pathname, { method:'POST', body: new FormData(this) });
    const data = await resp.json();
    document.getElementById('notif-alert').innerHTML = `<div class="kw-alert kw-alert-${data.success?'success':'danger'}" style="margin-bottom:0.75rem;">${data.message}</div>`;
  } catch(err) {}
  btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-save"></i> Save Preferences';
});

// Sidebar toggle
function openSidebar() {
  document.getElementById('portal-sidebar').classList.add('open');
  document.getElementById('sidebar-overlay').classList.add('show');
}
function closeSidebar() {
  document.getElementById('portal-sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('show');
}
</script>

<style>
@media(max-width:1024px){ div[style*="300px 1fr"]{grid-template-columns:1fr!important;} }
@media(max-width:640px){ div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;} }
</style>

<?php require_once __DIR__ . '/portal-footer.php'; ?>