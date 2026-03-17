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

csrfAbortIfInvalid();

if (!checkRateLimit('demo_request')) {
    jsonResponse(false, 'Too many requests. Please wait before trying again.', [], 429);
}

// ---- Collect & sanitize ----
$name            = trim(htmlspecialchars($_POST['name']             ?? '', ENT_QUOTES, 'UTF-8'));
$email           = strtolower(trim($_POST['email']                  ?? ''));
$phone           = trim(htmlspecialchars($_POST['phone']            ?? '', ENT_QUOTES, 'UTF-8'));
$company         = trim(htmlspecialchars($_POST['company']          ?? '', ENT_QUOTES, 'UTF-8'));
$productInterest = trim(htmlspecialchars($_POST['product_interest'] ?? '', ENT_QUOTES, 'UTF-8'));
$demoType        = trim($_POST['demo_type'] ?? 'live');
$preferredDate   = trim($_POST['preferred_date'] ?? '');
$preferredTime   = trim(htmlspecialchars($_POST['preferred_time']   ?? '', ENT_QUOTES, 'UTF-8'));
$message         = trim(htmlspecialchars($_POST['message']          ?? '', ENT_QUOTES, 'UTF-8'));

// ---- Validate ----
$errors = [];

if (empty($name) || mb_strlen($name) < 2)           $errors['name']    = 'Please enter your full name.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
if (empty($company))                                 $errors['company'] = 'Please enter your company name.';
if (empty($productInterest))                         $errors['product_interest'] = 'Please select a product you\'re interested in.';
if (!in_array($demoType, ['live','recorded','sandbox'])) $demoType = 'live';

// Validate preferred date if provided
if (!empty($preferredDate)) {
    $dateTs = strtotime($preferredDate);
    if ($dateTs === false || $dateTs < strtotime('today')) {
        $errors['preferred_date'] = 'Please select a future date.';
    }
}

// Honeypot
if (!empty($_POST['website'])) {
    jsonResponse(true, 'Demo request submitted successfully!');
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
        INSERT INTO inquiries
            (type, name, email, phone, company, product_interest, preferred_date, preferred_time, message, ip_address, user_agent)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        'demo', $name, $email, $phone, $company,
        $productInterest,
        !empty($preferredDate) ? date('Y-m-d', strtotime($preferredDate)) : null,
        $preferredTime, $message,
        $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    $inquiryId = $pdo->lastInsertId();
} catch (Exception $e) {
    error_log('Demo request DB error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to submit your request. Please try again.', [], 500);
}

// ---- Emails ----
$adminBody = renderTemplate('admin-inquiry', [
    'type'             => 'Demo Request',
    'name'             => $name,
    'email'            => $email,
    'phone'            => $phone,
    'company'          => $company,
    'subject'          => "Demo: {$productInterest} ({$demoType})",
    'message'          => nl2br($message ?: 'No additional message.'),
    'preferred_date'   => $preferredDate ?: 'Not specified',
    'preferred_time'   => $preferredTime ?: 'Flexible',
    'id'               => $inquiryId,
    'time'             => date('d M Y H:i'),
]);
sendMail(MAIL_ADMIN, APP_NAME . ' Team', "New Demo Request: {$productInterest} — {$name}", $adminBody);

$userBody = renderTemplate('demo-confirm', [
    'name'           => $name,
    'product'        => $productInterest,
    'demo_type'      => ucfirst($demoType),
    'preferred_date' => $preferredDate ? date('D, d M Y', strtotime($preferredDate)) : 'To be confirmed',
    'preferred_time' => $preferredTime ?: 'Flexible',
]);
sendMail($email, $name, "Demo Request Confirmed — {$productInterest}", $userBody);

jsonResponse(true, "Thank you {$name}! Your demo request has been received. Our team will contact you within 24 hours to confirm the schedule.");