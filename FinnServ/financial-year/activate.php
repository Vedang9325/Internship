<?php

declare(strict_types=1);

// Bootstrapping and session protection check.
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';


/*
|--------------------------------------------------------------------------
| Validate Request Method
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


// Filter and validate GET parameters to ensure the ID is a valid integer.
$financialYearId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

// Retrieve active company context.
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
| Execute Context Activation
|--------------------------------------------------------------------------
|
| Invokes the transactional SQL routine in the repository.
|
*/

try {

    activateFinancialYear(
        $pdo,
        $financialYearId,
        $companyId
    );

    // Set success alert message.
    setFlash(
        'success',
        'Financial year activated successfully.'
    );

} catch (Throwable $e) {

    // Catch errors like missing ID or failed transactions.
    setFlash(
        'error',
        'Unable to activate financial year.'
    );
}


/*
|--------------------------------------------------------------------------
| Redirect back to list
|--------------------------------------------------------------------------
*/

header(
    'Location: ' .
    BASE_URL .
    'financial-year/'
);

exit;