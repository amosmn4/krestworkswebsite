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

if (!checkRateLimit('newsletter', $_SERVER['REMOTE_ADDR'] ?? '')) {
    jsonResponse(false, 'Too many requests.', [], 429);
}

$email = strtolower(trim($_POST['email'] ?? ''));
$name  = trim(htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'));

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Please enter a valid email address.', ['fields' => ['email' => 'Invalid email address.']], 422);
}

if (strlen($email) > 150) {
    jsonResponse(false, 'Email address is too long.', [], 422);
}

if (!empty($_POST['website'])) {
    jsonResponse(true, 'Subscribed successfully!');
}

try {
    $pdo = db();

    // Check already subscribed
    $check = $pdo->prepare('SELECT id, is_active FROM newsletter_subscribers WHERE email = ? LIMIT 1');
    $check->execute([$email]);
    $existing = $check->fetch();

    if ($existing) {
        if ($existing['is_active']) {
            jsonResponse(false, 'This email is already subscribed. Thank you!');
        } else {
            // Re-activate
            $pdo->prepare('UPDATE newsletter_subscribers SET is_active = 1, name = ? WHERE email = ?')
                ->execute([$name ?: null, $email]);
            jsonResponse(true, 'Welcome back! You\'ve been re-subscribed to our newsletter.');
        }
    }

    $pdo->prepare('INSERT INTO newsletter_subscribers (email, name) VALUES (?, ?)')
        ->execute([$email, $name ?: null]);

    // Log inquiry
    $pdo->prepare('INSERT INTO inquiries (type, email, name) VALUES (?, ?, ?)')
        ->execute(['newsletter', $email, $name ?: null]);

} catch (Exception $e) {
    error_log('Newsletter DB error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to subscribe. Please try again.', [], 500);
}

// Send welcome email
$welcomeBody = renderTemplate('welcome', ['name' => $name ?: 'there', 'email' => $email]);
sendMail($email, $name ?: 'Subscriber', 'Welcome to Krestworks Insights!', $welcomeBody);

jsonResponse(true, 'You\'re subscribed! Expect valuable insights on AI, enterprise software, and digital transformation.');