<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';


/*
|--------------------------------------------------------------------------
| Collect Input
|--------------------------------------------------------------------------
*/

$name = trim($_POST['name'] ?? '');
$mailingName = trim($_POST['mailing_name'] ?? '');
$address = trim($_POST['address'] ?? '');
$financialYear = trim($_POST['financial_year'] ?? '');
$startDate = trim($_POST['start_date'] ?? '');
$booksBeginning = trim($_POST['books_beginning'] ?? '');
$state = trim($_POST['state'] ?? '');
$country = trim($_POST['country'] ?? 'India');
$pincode = trim($_POST['pincode'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$gstin = strtoupper(trim($_POST['gstin'] ?? ''));


/*
|--------------------------------------------------------------------------
| Preserve User Input
|--------------------------------------------------------------------------
*/

$old = [
    'name' => $name,
    'mailing_name' => $mailingName,
    'address' => $address,
    'financial_year' => $financialYear,
    'start_date' => $startDate,
    'books_beginning' => $booksBeginning,
    'state' => $state,
    'country' => $country,
    'pincode' => $pincode,
    'phone' => $phone,
    'email' => $email,
    'gstin' => $gstin,
];


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$errors = [];


/* Company name */

if ($name === '') {

    $errors['name'] = 'Company name is required.';

} elseif (mb_strlen($name) > 150) {

    $errors['name'] = 'Company name cannot exceed 150 characters.';

}


/* Financial year */

if ($financialYear === '') {

    $errors['financial_year'] = 'Financial year is required.';

} elseif (!preg_match('/^\d{4}-\d{2}$/', $financialYear)) {

    $errors['financial_year'] =
        'Use the format YYYY-YY, for example 2026-27.';

}


/* Start date */

$startDateObject = DateTime::createFromFormat(
    'Y-m-d',
    $startDate
);

if (
    $startDate === '' ||
    !$startDateObject ||
    $startDateObject->format('Y-m-d') !== $startDate
) {

    $errors['start_date'] = 'Enter a valid start date.';

}


/* Books beginning */

$booksBeginningObject = DateTime::createFromFormat(
    'Y-m-d',
    $booksBeginning
);

if (
    $booksBeginning === '' ||
    !$booksBeginningObject ||
    $booksBeginningObject->format('Y-m-d') !== $booksBeginning
) {

    $errors['books_beginning'] =
        'Enter a valid books beginning date.';

}


/* Financial year date consistency */

if (
    isset($startDateObject) &&
    $startDateObject instanceof DateTime &&
    preg_match('/^(\d{4})-(\d{2})$/', $financialYear, $matches)
) {

    $financialYearStartYear = (int) $matches[1];

    if (
        (int) $startDateObject->format('Y') !==
        $financialYearStartYear
    ) {

        $errors['start_date'] =
            'Start date must belong to the selected financial year.';

    }

}


/* Books beginning cannot be before FY start */

if (
    isset($startDateObject, $booksBeginningObject) &&
    $startDateObject instanceof DateTime &&
    $booksBeginningObject instanceof DateTime
) {

    if ($booksBeginningObject < $startDateObject) {

        $errors['books_beginning'] =
            'Books beginning cannot be before the financial year start date.';

    }

}


/* Email */

if (
    $email !== '' &&
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {

    $errors['email'] = 'Enter a valid email address.';

}


/* GSTIN */

if (
    $gstin !== '' &&
    !preg_match(
        '/^[0-9A-Z]{15}$/',
        $gstin
    )
) {

    $errors['gstin'] =
        'GSTIN must contain exactly 15 letters and numbers.';

}


/*
|--------------------------------------------------------------------------
| Return If Validation Failed
|--------------------------------------------------------------------------
*/

if (!empty($errors)) {

    $_SESSION['company_create_errors'] = $errors;

    $_SESSION['company_create_old'] = $old;

    header(
        'Location: ' .
        BASE_URL .
        'company/create.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Calculate Financial Year End Date
|--------------------------------------------------------------------------
*/

$endDateObject = clone $startDateObject;

$endDateObject->modify('+1 year');
$endDateObject->modify('-1 day');

$endDate = $endDateObject->format('Y-m-d');


/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

try {

    $pdo->beginTransaction();


    /*
    |--------------------------------------------------------------------------
    | Create Company
    |--------------------------------------------------------------------------
    */

    $companySql = "
        INSERT INTO companies (
            name,
            mailing_name,
            address,
            state,
            country,
            pincode,
            phone,
            email,
            gstin
        )
        VALUES (
            :name,
            :mailing_name,
            :address,
            :state,
            :country,
            :pincode,
            :phone,
            :email,
            :gstin
        )
    ";

    $companyStatement = $pdo->prepare($companySql);

    $companyStatement->execute([
        ':name' => $name,
        ':mailing_name' => $mailingName !== ''
            ? $mailingName
            : $name,
        ':address' => $address !== ''
            ? $address
            : null,
        ':state' => $state !== ''
            ? $state
            : null,
        ':country' => $country,
        ':pincode' => $pincode !== ''
            ? $pincode
            : null,
        ':phone' => $phone !== ''
            ? $phone
            : null,
        ':email' => $email !== ''
            ? $email
            : null,
        ':gstin' => $gstin !== ''
            ? $gstin
            : null,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Get New Company ID
    |--------------------------------------------------------------------------
    */

    $companyId = (int) $pdo->lastInsertId();


    /*
    |--------------------------------------------------------------------------
    | Create Initial Financial Year
    |--------------------------------------------------------------------------
    */

    $financialYearSql = "
        INSERT INTO financial_years (
            company_id,
            name,
            start_date,
            end_date,
            is_active
        )
        VALUES (
            :company_id,
            :name,
            :start_date,
            :end_date,
            1
        )
    ";

    $financialYearStatement =
        $pdo->prepare($financialYearSql);

    $financialYearStatement->execute([
        ':company_id' => $companyId,
        ':name' => $financialYear,
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Get New Financial Year ID
    |--------------------------------------------------------------------------
    */

    $financialYearId =
        (int) $pdo->lastInsertId();


    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $pdo->commit();


    /*
    |--------------------------------------------------------------------------
    | Set Active Company Context
    |--------------------------------------------------------------------------
    */

    $_SESSION['company_id'] = $companyId;

    $_SESSION['company_name'] = $name;

    $_SESSION['financial_year_id'] =
        $financialYearId;

    $_SESSION['financial_year_name'] =
        $financialYear;

    $_SESSION['financial_year_start'] =
        $startDate;

    $_SESSION['financial_year_end'] =
        $endDate;


    /*
    |--------------------------------------------------------------------------
    | Redirect To Gateway
    |--------------------------------------------------------------------------
    */

    header(
        'Location: ' .
        BASE_URL .
        'dashboard/'
    );

    exit;


} catch (Throwable $e) {


    /*
    |--------------------------------------------------------------------------
    | Roll Back Failed Transaction
    |--------------------------------------------------------------------------
    */

    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }


    /*
    |--------------------------------------------------------------------------
    | Development Error
    |--------------------------------------------------------------------------
    */

    $_SESSION['company_create_errors'] = [
        'general' =>
            'Unable to create company. ' .
            $e->getMessage(),
    ];

    $_SESSION['company_create_old'] = $old;


    header(
        'Location: ' .
        BASE_URL .
        'company/create.php'
    );

    exit;
}