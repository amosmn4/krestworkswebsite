<?php
require_once __DIR__ . '/../config/config.php';

function csrfGenerate(): string {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrfGenerate()) . '">';
}

function csrfVerify(): bool {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($token) || empty($_SESSION[CSRF_TOKEN_NAME])) return false;
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function csrfAbortIfInvalid(): void {
    if (!csrfVerify()) {
        jsonResponse(false, 'Invalid request token.', [], 403);
    }
}