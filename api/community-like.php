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

if (!checkRateLimit('community_like')) {
    jsonResponse(false, 'Too many likes. Please slow down.', [], 429);
}

$type = trim($_POST['type'] ?? '');
$id   = (int)($_POST['id'] ?? 0);

if (!in_array($type, ['post', 'reply']) || $id <= 0) {
    jsonResponse(false, 'Invalid request.', [], 400);
}

$userId = $_SESSION['user_id'];

try {
    $pdo = db();

    if ($type === 'post') {
        $item = $pdo->prepare("SELECT id, likes FROM community_posts WHERE id = ? AND is_active = 1 LIMIT 1");
        $item->execute([$id]);
        $row = $item->fetch();
        if (!$row) jsonResponse(false, 'Post not found.', [], 404);

        // Toggle like via session (simple approach without likes table)
        $sessionKey = "liked_post_{$id}";
        if (!empty($_SESSION[$sessionKey])) {
            // Unlike
            $pdo->prepare("UPDATE community_posts SET likes = GREATEST(0, likes - 1) WHERE id = ?")->execute([$id]);
            unset($_SESSION[$sessionKey]);
            $newLikes = max(0, $row['likes'] - 1);
            jsonResponse(true, 'Unliked.', ['likes' => $newLikes, 'liked' => false]);
        } else {
            // Like
            $pdo->prepare("UPDATE community_posts SET likes = likes + 1 WHERE id = ?")->execute([$id]);
            $_SESSION[$sessionKey] = true;
            $newLikes = $row['likes'] + 1;
            jsonResponse(true, 'Liked!', ['likes' => $newLikes, 'liked' => true]);
        }

    } else {
        $item = $pdo->prepare("SELECT id, likes FROM community_replies WHERE id = ? AND is_active = 1 LIMIT 1");
        $item->execute([$id]);
        $row = $item->fetch();
        if (!$row) jsonResponse(false, 'Reply not found.', [], 404);

        $sessionKey = "liked_reply_{$id}";
        if (!empty($_SESSION[$sessionKey])) {
            $pdo->prepare("UPDATE community_replies SET likes = GREATEST(0, likes - 1) WHERE id = ?")->execute([$id]);
            unset($_SESSION[$sessionKey]);
            jsonResponse(true, 'Unliked.', ['likes' => max(0, $row['likes'] - 1), 'liked' => false]);
        } else {
            $pdo->prepare("UPDATE community_replies SET likes = likes + 1 WHERE id = ?")->execute([$id]);
            $_SESSION[$sessionKey] = true;
            jsonResponse(true, 'Liked!', ['likes' => $row['likes'] + 1, 'liked' => true]);
        }
    }

} catch (Exception $e) {
    error_log('Community like error: ' . $e->getMessage());
    jsonResponse(false, 'Failed to update like.', [], 500);
}