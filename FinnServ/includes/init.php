<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Initialization & Bootstrapping
|--------------------------------------------------------------------------
|
| This file is the primary bootstrap script. Any page requiring access
| to configurations, the database, or session variables must include this.
|
*/

// Load global configuration constants.
require_once __DIR__ . '/../config/app.php';

// Initialize the database connection ($pdo).
require_once __DIR__ . '/../config/database.php';


/*
|--------------------------------------------------------------------------
| Session Management
|--------------------------------------------------------------------------
|
| Starts a new session or resumes the existing one if not already active.
| Used to keep track of logged-in user credentials, active company, etc.
|
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}