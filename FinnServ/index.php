<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard/');
    exit;
}

header('Location: ' . BASE_URL . 'auth/login.php');
exit;
