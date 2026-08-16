<?php

declare(strict_types=1);

// Include standard configurations and start session environment.
require_once __DIR__ . '/../includes/init.php';


/*
|--------------------------------------------------------------------------
| Logout Procedure
|--------------------------------------------------------------------------
|
| Clears all active variables from session memory, deletes the session
| identifier cookie in the browser, destroys the server session file,
| and redirects to the login screen. Matches the 'Shut Company' action.
|
*/

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

// Redirect user back to login page.
header('Location: ' . BASE_URL . 'auth/login.php');
exit;