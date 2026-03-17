<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.', [], 405);
}

$toolSlug = trim(htmlspecialchars($_POST['tool_slug'] ?? '', ENT_QUOTES, 'UTF-8'));
if (empty($toolSlug)) {
    jsonResponse(false, 'Tool slug required.', [], 400);
}

try {
    $pdo = db();
    $pdo->prepare('INSERT INTO ai_tool_usage (tool_slug, user_id, ip_address) VALUES (?, ?, ?)')
        ->execute([
            $toolSlug,
            $_SESSION['user_id'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? ''
        ]);

    // Log site event
    $pdo->prepare('INSERT INTO site_events (event_type, page, user_id, ip_address) VALUES (?, ?, ?, ?)')
        ->execute(['tool_use', "/ai-hub/{$toolSlug}", $_SESSION['user_id'] ?? null, $_SERVER['REMOTE_ADDR'] ?? '']);
} catch (Exception $e) {
    error_log('Tool usage log error: ' . $e->getMessage());
}

jsonResponse(true, 'Logged.');