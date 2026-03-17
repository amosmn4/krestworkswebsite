<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/rate_limiter.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../mail/mailer.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.', [], 405);
}

csrfAbortIfInvalid();

if (!checkRateLimit('register')) {
    jsonResponse(false, 'Too many registration attempts.', [], 429);
}

$name     = trim(htmlspecialchars($_POST['name']     ?? '', ENT_QUOTES, 'UTF-8'));
$email    = strtolower(trim($_POST['email']          ?? ''));
$password = $_POST['password']                       ?? '';
$confirm  = $_POST['password_confirm']               ?? '';
$company  = trim(htmlspecialchars($_POST['company']  ?? '', ENT_QUOTES, 'UTF-8'));
$phone    = trim(htmlspecialchars($_POST['phone']    ?? '', ENT_QUOTES, 'UTF-8'));

$errors = [];

if (empty($name) || mb_strlen($name) < 2)           $errors['name']     = 'Please enter your full name.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
if (strlen($password) < 8)                          $errors['password'] = 'Password must be at least 8 characters.';
if (!preg_match('/[A-Z]/', $password))              $errors['password'] = 'Password must contain at least one uppercase letter.';
if (!preg_match('/[0-9]/', $password))              $errors['password'] = 'Password must contain at least one number.';
if ($password !== $confirm)                          $errors['password_confirm'] = 'Passwords do not match.';

if (!empty($_POST['website'])) {
    jsonResponse(true, 'Registered successfully!');
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please fix the errors below.', 'fields' => $errors]);
    exit;
}

$result = registerUser($name, $email, $password, $company);

if (!$result['success']) {
    jsonResponse(false, $result['message'], ['fields' => ['email' => $result['message']]], 422);
}

// Update phone if provided
if (!empty($phone)) {
    try {
        db()->prepare('UPDATE users SET phone = ? WHERE id = ?')->execute([$phone, $result['id']]);
    } catch (Exception $e) {
        error_log('Register phone update error: ' . $e->getMessage());
    }
}

// Send welcome email
$welcomeBody = renderTemplate('welcome', ['name' => $name, 'email' => $email]);
sendMail($email, $name, 'Welcome to ' . APP_NAME . '!', $welcomeBody);

// Notify admin
sendMail(MAIL_ADMIN, APP_NAME . ' Team',
    "New Registration: {$name} <{$email}>",
    "<p>New client registered: <strong>{$name}</strong> ({$email})" . ($company ? " from <em>{$company}</em>" : '') . "</p>"
);

jsonResponse(true, 'Account created successfully! You can now log in.', ['redirect' => url('portal/login')]);