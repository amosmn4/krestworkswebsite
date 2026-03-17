<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    private static ?PDO $instance = null;

    public static function connect(): PDO {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (APP_ENV === 'development') {
                    die('DB Connection failed: ' . $e->getMessage());
                } else {
                    die('Service temporarily unavailable. Please try again later.');
                }
            }
        }
        return self::$instance;
    }

    // Prevent cloning
    private function __clone() {}
    private function __construct() {}
}

// Shorthand helper
function db(): PDO {
    return Database::connect();
}