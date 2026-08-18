<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

// Verify active session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all active session values
$_SESSION = [];

// Delete active session cookie in user browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session context completely
session_destroy();

// Redirect back to login screen
header('Location: ' . BASE_URL . 'auth/login.php');
exit;