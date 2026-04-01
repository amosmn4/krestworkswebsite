<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false]); exit; }

session_name(SESSION_NAME);
if (session_status() === PHP_SESSION_NONE) session_start();

$jobId = (int)($_POST['job_id'] ?? 0);
$data  = $_POST['data'] ?? '{}';

if ($jobId <= 0) { echo json_encode(['success'=>false]); exit; }

try {
    $decoded = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) throw new Exception('Invalid JSON');
    // Sanitize all string values
    array_walk_recursive($decoded, function(&$v) { if (is_string($v)) $v = htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); });
    $_SESSION['app_drafts']['app_draft_' . $jobId] = $decoded;
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
