<?php

declare(strict_types=1);

// Include standard configurations and start session environment.
require_once __DIR__ . '/../includes/init.php';


// Logout Procedure

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all active session variables from memory.
$_SESSION = [];

// Delete the browser session cookie by setting its expiry time in the past.
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

// Destroy session data on the server file structure.
session_destroy();

header('Location: ' . BASE_URL . 'auth/login.php');
exit;