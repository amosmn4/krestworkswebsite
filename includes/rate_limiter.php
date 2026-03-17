<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/db.php';

function checkRateLimit(string $action, string $ip = ''): bool {
    if (empty($ip)) $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $pdo = db();

    // Clean old records
    $pdo->prepare('DELETE FROM rate_limits WHERE created_at < DATE_SUB(NOW(), INTERVAL ? SECOND)')
        ->execute([RATE_LIMIT_WINDOW]);

    // Count recent hits
    $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM rate_limits WHERE ip_address = ? AND action = ?');
    $stmt->execute([$ip, $action]);
    $row = $stmt->fetch();

    if ($row['cnt'] >= RATE_LIMIT_MAX) return false;

    // Log this request
    $pdo->prepare('INSERT INTO rate_limits (ip_address, action) VALUES (?, ?)')
        ->execute([$ip, $action]);

    return true;
}