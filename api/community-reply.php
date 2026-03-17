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

if (!checkRateLimit('community_reply')) {
    jsonResponse(false, 'You\'re replying too fast. Please wait a moment.', [], 429);
}

$postId = (int)($_POST['post_id'] ?? 0);
$body   = trim(htmlspecialchars($_POST['body'] ?? '', ENT_QUOTES, 'UTF-8'));

$errors = [];
if ($postId <= 0)                           $errors['post_id'] = 'Invalid post.';
if (empty($body) || mb_strlen($body) < 5)  $errors['body']    = 'Reply must be at least 5 characters.';
if (mb_strlen($body) > 5000)               $errors['body']    = 'Reply must be under 5000 characters.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please fix the errors.', 'fields' => $errors]);
    exit;
}

try {
    $pdo = db();

    // Verify post exists and is active
    $post = $pdo->prepare('SELECT id, user_id, title FROM community_posts WHERE id = ? AND is_active = 1 LIMIT 1');
    $post->execute([$postId]);
    $postRow = $post->fetch();

    if (!$postRow) {
        jsonResponse(false, 'Post not found or no longer active.', [], 404);
    }

    // Insert reply
    $stmt = $pdo->prepare('INSERT INTO community_replies (post_id, user_id, body) VALUES (?, ?, ?)');
    $stmt->execute([$postId, $_SESSION['user_id'], $body]);
    $replyId = $pdo->lastInsertId();

    // Get reply with user info for immediate render
    $replyData = $pdo->prepare('
        SELECT r.id, r.body, r.created_at, r.likes,
               u.name as author_name, u.id as author_id
        FROM community_replies r
        JOIN users u ON u.id = r.user_id
        WHERE r.id = ?
    ');
    $replyData->execute([$replyId]);
    $reply = $replyData->fetch();

} catch (Exception $e) {
    error_log('Community reply error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to post reply. Please try again.', [], 500);
}

jsonResponse(true, 'Reply posted!', [
    'reply' => [
        'id'          => $reply['id'],
        'body'        => $reply['body'],
        'author_name' => $reply['author_name'],
        'author_id'   => $reply['author_id'],
        'likes'       => 0,
        'time_ago'    => 'just now',
        'initial'     => strtoupper(mb_substr($reply['author_name'], 0, 1)),
    ],
]);