<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

// Redirect to dashboard based on user role
if (isset($_SESSION['user_role'])) {
	$redirectPath = $_SESSION['user_role'] === 'admin' ? '/admin' : '/portal/dashboard';
	header('Location: ' . $redirectPath);
	exit();
} else {
	header('Location: /login');
	exit();
}