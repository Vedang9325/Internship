<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/validator.php';


/*
|--------------------------------------------------------------------------
| Request Method
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header(
        'Location: ' .
        BASE_URL .
        'financial-year/'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Current Company
|--------------------------------------------------------------------------
*/

$companyId = (int) $_SESSION['company_id'];


/*
|--------------------------------------------------------------------------
| Form Data Sanitization
|--------------------------------------------------------------------------
*/

$data = [

    'name' =>
        trim($_POST['name'] ?? ''),

    'start_date' =>
        trim($_POST['start_date'] ?? ''),

    'end_date' =>
        trim($_POST['end_date'] ?? ''),

];


/*
|--------------------------------------------------------------------------
| Validate Input Range Boundaries
|--------------------------------------------------------------------------
*/

$errors = validateFinancialYear($data);

// If inputs fail date rules, store details in session cache and redirect back (PRG).
if (!empty($errors)) {

    $_SESSION['financial_year_errors'] = $errors;

    $_SESSION['financial_year_old'] = $data;

    header(
        'Location: ' .
        BASE_URL .
        'financial-year/create.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Duplicate Name Check
|--------------------------------------------------------------------------
|
| Prevents adding multiple periods with identical labels.
|
*/

if (
    financialYearExists(
        $pdo,
        $companyId,
        $data['name']
    )
) {

    // Show warning banner to the user.
    setFlash(
        'error',
        'A financial year with this name already exists.'
    );

    $_SESSION['financial_year_old'] = $data;

    header(
        'Location: ' .
        BASE_URL .
        'financial-year/create.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Database Insert execution
|--------------------------------------------------------------------------
*/

try {

    createFinancialYear(
        $pdo,
        $companyId,
        $data
    );

    // Set success banner message.
    setFlash(
        'success',
        'Financial year created successfully.'
    );

    // Redirect to list index page on success.
    header(
        'Location: ' .
        BASE_URL .
        'financial-year/'
    );

    exit;

} catch (Throwable $e) {

    // Catch database connection failures or integrity checks.
    setFlash(
        'error',
        'Unable to create financial year.'
    );

    header(
        'Location: ' .
        BASE_URL .
        'financial-year/create.php'
    );

    exit;
}