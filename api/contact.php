<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/rate_limiter.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../mail/mailer.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.', [], 405);
}

// CSRF
csrfAbortIfInvalid();

// Rate limit
if (!checkRateLimit('contact_form')) {
    jsonResponse(false, 'Too many submissions. Please wait before trying again.', [], 429);
}

// ---- Collect & sanitize ----
$name    = trim(htmlspecialchars($_POST['name']    ?? '', ENT_QUOTES, 'UTF-8'));
$email   = strtolower(trim($_POST['email']         ?? ''));
$subject = trim(htmlspecialchars($_POST['subject'] ?? '', ENT_QUOTES, 'UTF-8'));
$message = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));
$phone   = trim(htmlspecialchars($_POST['phone']   ?? '', ENT_QUOTES, 'UTF-8'));
$company = trim(htmlspecialchars($_POST['company'] ?? '', ENT_QUOTES, 'UTF-8'));

// ---- Validate ----
$errors = [];

if (empty($name) || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter your full name (at least 2 characters).';
}
if (mb_strlen($name) > 100) {
    $errors['name'] = 'Name must be under 100 characters.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}
if (strlen($email) > 150) {
    $errors['email'] = 'Email address is too long.';
}
if (empty($subject) || mb_strlen($subject) < 3) {
    $errors['subject'] = 'Please enter a subject (at least 3 characters).';
}
if (mb_strlen($subject) > 255) {
    $errors['subject'] = 'Subject must be under 255 characters.';
}
if (empty($message) || mb_strlen($message) < 10) {
    $errors['message'] = 'Please enter your message (at least 10 characters).';
}
if (mb_strlen($message) > 5000) {
    $errors['message'] = 'Message must be under 5000 characters.';
}

// Honeypot check (add hidden field named "website" in form)
if (!empty($_POST['website'])) {
    jsonResponse(true, 'Message sent successfully!'); // Silently accept bot
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please fix the errors below.', 'fields' => $errors]);
    exit;
}

// ---- Save to DB ----
try {
    $pdo = db();
    $stmt = $pdo->prepare('
        INSERT INTO inquiries (type, name, email, phone, company, subject, message, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        'contact', $name, $email, $phone, $company,
        $subject, $message,
        $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    $inquiryId = $pdo->lastInsertId();
} catch (Exception $e) {
    error_log('Contact DB error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to save your message. Please try again.', [], 500);
}

// ---- Send emails ----
// Admin notification
$adminSubject = "New Contact Form: {$subject}";
$adminBody    = renderTemplate('admin-inquiry', [
    'type'    => 'Contact Form',
    'name'    => $name,
    'email'   => $email,
    'phone'   => $phone,
    'company' => $company,
    'subject' => $subject,
    'message' => nl2br($message),
    'id'      => $inquiryId,
    'time'    => date('d M Y H:i'),
]);
sendMail(MAIL_ADMIN, APP_NAME . ' Team', $adminSubject, $adminBody);

// User confirmation
$userSubject = "We received your message — " . APP_NAME;
$userBody    = renderTemplate('contact-confirm', [
    'name'    => $name,
    'subject' => $subject,
    'message' => nl2br($message),
]);
sendMail($email, $name, $userSubject, $userBody);

jsonResponse(true, "Thank you {$name}! Your message has been received. We'll respond within 24 hours.");