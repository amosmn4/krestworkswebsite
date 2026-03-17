<?php
require_once __DIR__ . '/../config/config.php';

// ---- OUTPUT ----

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect(string $path): void {
    header('Location: ' . url($path));
    exit;
}

function isActivePage(string $segment): string {
    $uri = strtok($_SERVER['REQUEST_URI'], '?');
    $uri = trim(str_replace(parse_url(BASE_URL, PHP_URL_PATH), '', $uri), '/');
    return ($uri === trim($segment, '/')) ? 'active' : '';
}

function isActiveSection(string $prefix): string {
    $uri = strtok($_SERVER['REQUEST_URI'], '?');
    $uri = trim(str_replace(parse_url(BASE_URL, PHP_URL_PATH), '', $uri), '/');
    return str_starts_with($uri, trim($prefix, '/')) ? 'active' : '';
}

// ---- SLUGS ----

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
    $text = preg_replace('/[\s\-]+/', '-', $text);
    return trim($text, '-');
}

// ---- DATE ----

function formatDate(string $date, string $format = 'M j, Y'): string {
    return date($format, strtotime($date));
}

function timeAgo(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return formatDate($datetime);
}

// ---- JSON API RESPONSE ----

function jsonResponse(bool $success, string $message = '', array $data = [], int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data,
    ]);
    exit;
}

// ---- AUTH HELPERS ----

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function isAdmin(): bool {
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('portal/login');
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        redirect('admin/login');
    }
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    return [
        'id'    => $_SESSION['user_id'],
        'name'  => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role'  => $_SESSION['user_role'] ?? 'client',
    ];
}

// ---- STRING HELPERS ----

function truncate(string $text, int $limit = 150, string $suffix = '...'): string {
    if (mb_strlen($text) <= $limit) return $text;
    return mb_substr($text, 0, $limit) . $suffix;
}

function readingTime(string $text): string {
    $words = str_word_count(strip_tags($text));
    $minutes = ceil($words / 200);
    return $minutes . ' min read';
}