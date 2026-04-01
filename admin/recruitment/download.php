<?php
/**
 * admin/recruitment/download.php
 * Secure document download — admin only, validates file ownership.
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

session_name(SESSION_NAME);
if (session_status() === PHP_SESSION_NONE) session_start();

// Admin auth check
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    die('Access denied.');
}

$filePath = $_GET['file']   ?? '';
$appId    = (int)($_GET['app_id'] ?? 0);

// Sanitize path — no directory traversal
$filePath = ltrim(str_replace(['..', '\\', "\0"], '', $filePath), '/');

if (empty($filePath) || $appId <= 0) {
    http_response_code(400);
    die('Invalid request.');
}

$pdo = db();

// Verify the file belongs to the stated application (prevents enumeration)
try {
    // Check application_documents table
    $docCheck = $pdo->prepare("SELECT d.original_name, d.mime_type FROM application_documents d WHERE d.application_id = ? AND d.file_path = ? LIMIT 1");
    $docCheck->execute([$appId, $filePath]);
    $docRow = $docCheck->fetch(PDO::FETCH_ASSOC);

    if (!$docRow) {
        // Also check academic / professional qualification documents
        $acadCheck = $pdo->prepare("SELECT 'doc' AS type FROM application_academic_qualifications WHERE application_id = ? AND document_path = ? LIMIT 1");
        $acadCheck->execute([$appId, $filePath]);
        $acadRow = $acadCheck->fetch();

        if (!$acadRow) {
            $profCheck = $pdo->prepare("SELECT 'doc' AS type FROM application_professional_qualifications WHERE application_id = ? AND document_path = ? LIMIT 1");
            $profCheck->execute([$appId, $filePath]);
            $profRow = $profCheck->fetch();
            if (!$profRow) {
                http_response_code(404);
                die('Document not found or access denied.');
            }
        }
    }
} catch (Exception $e) {
    error_log('Download check error: ' . $e->getMessage());
    http_response_code(500);
    die('Server error.');
}

// Build full path
$fullPath = UPLOAD_PATH . $filePath;

// Final security checks
if (!file_exists($fullPath) || !is_file($fullPath)) {
    http_response_code(404);
    die('File not found.');
}

// Ensure file is within allowed upload directory (no path traversal)
$realUpload = realpath(UPLOAD_PATH);
$realFile   = realpath($fullPath);
if (!$realFile || strpos($realFile, $realUpload) !== 0) {
    http_response_code(403);
    die('Access denied.');
}

// Determine MIME type
$mime = $docRow['mime_type'] ?? mime_content_type($fullPath) ?? 'application/octet-stream';
$originalName = $docRow['original_name'] ?? basename($fullPath);

// Allow only safe MIME types
$allowedMimes = [
    'application/pdf', 'image/jpeg', 'image/png', 'image/jpg',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
if (!in_array($mime, $allowedMimes)) {
    http_response_code(403);
    die('File type not allowed for download.');
}

// Log download
try {
    $pdo->prepare("INSERT INTO site_events (event_type, page, user_id, ip_address) VALUES (?,?,?,?)")
        ->execute(['doc_download', "application:{$appId}:{$filePath}", $_SESSION['user_id'], $_SERVER['REMOTE_ADDR'] ?? '']);
} catch (Exception $e) { /* non-critical */ }

// Serve file
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9._\-]/', '_', $originalName) . '"');
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('X-Content-Type-Options: nosniff');

readfile($fullPath);
exit;