<?php
// ============================================================
// KRESTWORKS SOLUTIONS — GLOBAL CONFIGURATION
// ============================================================

// Environment: 'development' | 'production'
define('APP_ENV', 'development');

// Application
define('APP_NAME', 'Krestworks Solutions');
define('APP_TAGLINE', 'Digital Systems for Modern Businesses');
define('APP_DESCRIPTION', 'AI-powered enterprise software engineered for growth.');
define('BASE_URL', 'http://localhost/krestworks'); // No trailing slash. Change in production.
define('APP_VERSION', '1.0.0');

// Database
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'krestworks_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Mail (SMTP)
define('MAIL_HOST', 'smtp.sendgrid.net');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_USER', 'apikey');
define('MAIL_PASS', '');
define('MAIL_FROM', 'hello@krestworks.com');
define('MAIL_FROM_NAME', APP_NAME);
define('MAIL_ADMIN', 'admin@krestworks.com');

// WhatsApp
define('WHATSAPP_NUMBER', '2547011903289'); // No + prefix for wa.me links
define('WHATSAPP_DISPLAY', '+254 701 190 3289');

// AI (Anthropic)
define('AI_API_KEY', '');
define('AI_ENDPOINT', 'https://api.anthropic.com/v1/messages');
define('AI_MODEL', 'claude-opus-4-6');
define('AI_MAX_TOKENS', 1024);

// Uploads
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', BASE_URL . '/assets/uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Session
define('SESSION_NAME', 'krest_session');
define('SESSION_LIFETIME', 86400); // 24 hours

// Security
define('CSRF_TOKEN_NAME', 'krest_csrf');
define('BCRYPT_COST', 12);
define('RATE_LIMIT_WINDOW', 3600); // 1 hour in seconds
define('RATE_LIMIT_MAX', 10);       // Max requests per window per IP

// Social Links
define('SOCIAL_LINKEDIN', 'https://linkedin.com/company/krestworks');
define('SOCIAL_TWITTER', 'https://twitter.com/krestworks');
define('SOCIAL_GITHUB', 'https://github.com/krestworks');
define('SOCIAL_YOUTUBE', 'https://youtube.com/@krestworks');
define('SOCIAL_INSTAGRAM', 'https://instagram.com/krestworks');

// Company Info
define('COMPANY_EMAIL', 'info@krestworks.com');
define('COMPANY_PHONE', '+254 711 903 289');
define('COMPANY_ADDRESS', 'Nairobi, Kenya');
define('COMPANY_FOUNDED', '2024');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('PAGES_PATH', ROOT_PATH . '/pages');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('MAIL_TEMPLATES_PATH', ROOT_PATH . '/mail/templates');

// Error reporting
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/error.log');
}

// Start session
ini_set('session.name', SESSION_NAME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}