<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/rate_limiter.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.', [], 405);
}

csrfAbortIfInvalid();

if (!checkRateLimit('login')) {
    jsonResponse(false, 'Too many login attempts. Please wait and try again.', [], 429);
}

$email    = strtolower(trim($_POST['email']    ?? ''));
$password = $_POST['password']                 ?? '';
$remember = !empty($_POST['remember']);

$errors = [];
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
if (empty($password))                                             $errors['password'] = 'Please enter your password.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please fix the errors below.', 'fields' => $errors]);
    exit;
}

$result = loginUser($email, $password);

if (!$result['success']) {
    jsonResponse(false, $result['message'], ['fields' => ['email' => $result['message']]], 401);
}

// Remember me cookie (7 days)
if ($remember) {
    $token = bin2hex(random_bytes(32));
    setcookie('kw_remember', $token, time() + 604800, '/', '', APP_ENV === 'production', true);
    try {
        db()->prepare('UPDATE users SET reset_token = ? WHERE id = ?')->execute([$token, $_SESSION['user_id']]);
    } catch (Exception $e) { /* non-critical */ }
}

$redirect = $result['role'] === 'admin' ? url('admin') : url('portal');
jsonResponse(true, 'Login successful!', ['redirect' => $redirect]);