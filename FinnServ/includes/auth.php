<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Authentication Guard (Middleware)
|--------------------------------------------------------------------------
|
| This script verifies if the user is authenticated. Including this file at
| the beginning of a page makes it private. Unauthenticated users are
| automatically redirected to the login screen.
|
*/

// Resume/start session if not already done.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user session identifier is missing.
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit;
}