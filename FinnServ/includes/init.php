<?php
declare(strict_types=1);

// Load app settings and database config
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching of dynamic content
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
