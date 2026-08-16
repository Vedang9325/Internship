<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database Configuration Credentials
|--------------------------------------------------------------------------
|
| Configuration keys for MySQL database connection.
|
*/

define('DB_HOST', 'localhost');
define('DB_NAME', 'finnserv');
define('DB_USER', 'root');
define('DB_PASS', '');


/*
|--------------------------------------------------------------------------
| Database Connection (PDO)
|--------------------------------------------------------------------------
|
| Establishes connection using PHP Data Objects (PDO) instead of mysqli.
| PDO is safer and supports structured prepared statements, protecting
| against SQL injection.
|
*/

try {

    // Initialize PDO. Charset utf8mb4 supports proper text encoding (e.g., currency symbols, emojis).
    $pdo = new PDO(
        "mysql:host=" . DB_HOST .
        ";dbname=" . DB_NAME .
        ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );

    // Throw exceptions when queries fail. Essential for debugging in development.
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Fetch records as associative arrays (e.g., $row['column_name']) by default.
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    // Disable emulated prepares. Ensures query execution is native to the DB engine.
    $pdo->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $e) {

    // In development mode, display MySQL detailed error message (like missing db error).
    if (defined('APP_ENV') && APP_ENV === 'development') {
        die(
            "Database connection failed: " .
            htmlspecialchars($e->getMessage())
        );
    }

    // Secure fallback error message for production.
    die("Database connection failed.");

}