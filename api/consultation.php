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

if (!checkRateLimit('consultation')) {
    jsonResponse(false, 'Too many requests. Please wait before trying again.', [], 429);
}

// ---- Collect & sanitize ----
$name               = trim(htmlspecialchars($_POST['name']                ?? '', ENT_QUOTES, 'UTF-8'));
$email              = strtolower(trim($_POST['email']                     ?? ''));
$phone              = trim(htmlspecialchars($_POST['phone']               ?? '', ENT_QUOTES, 'UTF-8'));
$company            = trim(htmlspecialchars($_POST['company']             ?? '', ENT_QUOTES, 'UTF-8'));
$consultationType   = trim(htmlspecialchars($_POST['consultation_type']   ?? '', ENT_QUOTES, 'UTF-8'));
$serviceInterest    = trim(htmlspecialchars($_POST['service_interest']    ?? '', ENT_QUOTES, 'UTF-8'));
$preferredDate      = trim($_POST['preferred_date'] ?? '');
$preferredTime      = trim(htmlspecialchars($_POST['preferred_time']      ?? '', ENT_QUOTES, 'UTF-8'));
$budget             = trim(htmlspecialchars($_POST['budget']              ?? '', ENT_QUOTES, 'UTF-8'));
$teamSize           = trim(htmlspecialchars($_POST['team_size']           ?? '', ENT_QUOTES, 'UTF-8'));
$message            = trim(htmlspecialchars($_POST['message']             ?? '', ENT_QUOTES, 'UTF-8'));

// ---- Validate ----
$errors = [];
$validConsultationTypes = [
    'digital-transformation',
    'system-design',
    'ai-implementation',
    'software-modernization',
    'business-automation',
    'general',
];

if (empty($name) || mb_strlen($name) < 2)           $errors['name']  = 'Please enter your full name.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
if (empty($phone))                                   $errors['phone'] = 'Please enter your phone number.';
if (empty($consultationType) || !in_array($consultationType, $validConsultationTypes)) {
    $errors['consultation_type'] = 'Please select a consultation type.';
}
if (empty($message) || mb_strlen($message) < 20)    $errors['message'] = 'Please describe your needs (at least 20 characters).';

if (!empty($preferredDate)) {
    $dateTs = strtotime($preferredDate);
    if ($dateTs === false || $dateTs < strtotime('today')) {
        $errors['preferred_date'] = 'Please select a future date.';
    }
}

if (!empty($_POST['website'])) {
    jsonResponse(true, 'Consultation request submitted!');
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
            (type, name, email, phone, company, consultation_type, service_interest,
             preferred_date, preferred_time, message, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        'consultation', $name, $email, $phone, $company,
        $consultationType, $serviceInterest,
        !empty($preferredDate) ? date('Y-m-d', strtotime($preferredDate)) : null,
        $preferredTime, $message,
        $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    $inquiryId = $pdo->lastInsertId();
} catch (Exception $e) {
    error_log('Consultation DB error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to submit your request. Please try again.', [], 500);
}

// ---- Emails ----
$consultationLabel = ucwords(str_replace('-', ' ', $consultationType));

$adminBody = renderTemplate('admin-inquiry', [
    'type'              => 'Consultation Request',
    'name'              => $name,
    'email'             => $email,
    'phone'             => $phone,
    'company'           => $company,
    'subject'           => "Consultation: {$consultationLabel}",
    'message'           => nl2br($message),
    'preferred_date'    => $preferredDate ?: 'Not specified',
    'preferred_time'    => $preferredTime ?: 'Flexible',
    'budget'            => $budget ?: 'Not disclosed',
    'team_size'         => $teamSize ?: 'Not specified',
    'id'                => $inquiryId,
    'time'              => date('d M Y H:i'),
]);
sendMail(MAIL_ADMIN, APP_NAME . ' Team', "New Consultation: {$consultationLabel} — {$name}", $adminBody);

$userBody = renderTemplate('consultation-confirm', [
    'name'              => $name,
    'consultation_type' => $consultationLabel,
    'preferred_date'    => $preferredDate ? date('D, d M Y', strtotime($preferredDate)) : 'To be confirmed',
    'preferred_time'    => $preferredTime ?: 'Flexible',
    'message'           => nl2br($message),
]);
sendMail($email, $name, "Consultation Request Confirmed — " . APP_NAME, $userBody);

jsonResponse(true, "Thank you {$name}! Your consultation request has been received. Our team will reach out within 24 hours to confirm your session.");