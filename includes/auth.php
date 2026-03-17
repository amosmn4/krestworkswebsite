<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function loginUser(string $email, string $password): array {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, name, email, password, role, is_active FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([trim(strtolower($email))]);
    $user = $stmt->fetch();

    if (!$user) return ['success' => false, 'message' => 'Invalid email or password.'];
    if (!$user['is_active']) return ['success' => false, 'message' => 'Account is deactivated.'];
    if (!password_verify($password, $user['password'])) return ['success' => false, 'message' => 'Invalid email or password.'];

    // Set session
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role'];
    session_regenerate_id(true);

    // Update last login
    $pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);

    return ['success' => true, 'role' => $user['role']];
}

function logoutUser(): void {
    $_SESSION = [];
    session_destroy();
}

function registerUser(string $name, string $email, string $password, string $company = ''): array {
    $pdo = db();

    // Check existing
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([strtolower($email)]);
    if ($stmt->fetch()) return ['success' => false, 'message' => 'Email already registered.'];

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, company, role) VALUES (?, ?, ?, ?, "client")');
    $stmt->execute([trim($name), strtolower(trim($email)), $hash, trim($company)]);

    return ['success' => true, 'id' => $pdo->lastInsertId()];
}