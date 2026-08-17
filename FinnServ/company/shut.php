<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';


/*
|--------------------------------------------------------------------------
| Clear Active Company Context
|--------------------------------------------------------------------------
*/

unset($_SESSION['company_id']);
unset($_SESSION['company_name']);

unset($_SESSION['financial_year_id']);
unset($_SESSION['financial_year_name']);
unset($_SESSION['financial_year_start']);
unset($_SESSION['financial_year_end']);


/*
|--------------------------------------------------------------------------
| Redirect To Company Selection
|--------------------------------------------------------------------------
*/

header(
    'Location: ' .
    BASE_URL .
    'company/select.php'
);

exit;