<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';


/*
|--------------------------------------------------------------------------
| Validate Request
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    header(
        'Location: ' .
        BASE_URL .
        'financial-year/'
    );

    exit;
}


$financialYearId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

$companyId = (int) $_SESSION['company_id'];


if (!$financialYearId) {

    setFlash(
        'error',
        'Invalid financial year.'
    );

    header(
        'Location: ' .
        BASE_URL .
        'financial-year/'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Activate
|--------------------------------------------------------------------------
*/

try {

    activateFinancialYear(
        $pdo,
        $financialYearId,
        $companyId
    );

    setFlash(
        'success',
        'Financial year activated successfully.'
    );

} catch (Throwable $e) {

    setFlash(
        'error',
        'Unable to activate financial year.'
    );
}


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header(
    'Location: ' .
    BASE_URL .
    'financial-year/'
);

exit;