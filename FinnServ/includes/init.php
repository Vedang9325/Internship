<?php

declare(strict_types=1);


require_once __DIR__ . '/../config/app.php';

require_once __DIR__ . '/../config/database.php';



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
