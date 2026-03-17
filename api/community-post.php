<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/rate_limiter.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.', [], 405);
}

csrfAbortIfInvalid();
requireLogin();

if (!checkRateLimit('community_post')) {
    jsonResponse(false, 'You\'re posting too fast. Please wait a moment.', [], 429);
}

$title    = trim(htmlspecialchars($_POST['title']    ?? '', ENT_QUOTES, 'UTF-8'));
$body     = trim(htmlspecialchars($_POST['body']     ?? '', ENT_QUOTES, 'UTF-8'));
$category = trim($_POST['category']                  ?? 'discussion');
$tagsRaw  = trim($_POST['tags']                      ?? '');

$validCategories = ['discussion','question','insight','tutorial','news'];

$errors = [];
if (empty($title) || mb_strlen($title) < 5)   $errors['title']    = 'Title must be at least 5 characters.';
if (mb_strlen($title) > 255)                  $errors['title']    = 'Title must be under 255 characters.';
if (empty($body) || mb_strlen($body) < 20)    $errors['body']     = 'Post content must be at least 20 characters.';
if (mb_strlen($body) > 10000)                 $errors['body']     = 'Post content must be under 10,000 characters.';
if (!in_array($category, $validCategories))   $category = 'discussion';

// Parse tags (comma-separated, max 5 tags)
$tags = [];
if (!empty($tagsRaw)) {
    $tags = array_slice(
        array_map('trim', array_filter(explode(',', $tagsRaw))),
        0, 5
    );
    $tags = array_map(fn($t) => htmlspecialchars(strtolower(substr($t, 0, 30)), ENT_QUOTES, 'UTF-8'), $tags);
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please fix the errors below.', 'fields' => $errors]);
    exit;
}

try {
    $pdo = db();
    $stmt = $pdo->prepare('
        INSERT INTO community_posts (user_id, title, body, category, tags)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $_SESSION['user_id'],
        $title, $body, $category,
        !empty($tags) ? json_encode($tags) : null,
    ]);
    $postId = $pdo->lastInsertId();
} catch (Exception $e) {
    error_log('Community post error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to create post. Please try again.', [], 500);
}

jsonResponse(true, 'Post published successfully!', ['redirect' => url("community/post/{$postId}")]);