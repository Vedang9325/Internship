<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';


/*
|--------------------------------------------------------------------------
| Collect Input
|--------------------------------------------------------------------------
*/

$companyId = filter_input(
    INPUT_POST,
    'company_id',
    FILTER_VALIDATE_INT
);

$name = trim($_POST['name'] ?? '');
$mailingName = trim($_POST['mailing_name'] ?? '');
$address = trim($_POST['address'] ?? '');
$state = trim($_POST['state'] ?? '');
$country = trim($_POST['country'] ?? 'India');
$pincode = trim($_POST['pincode'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$gstin = strtoupper(trim($_POST['gstin'] ?? ''));


/*
|--------------------------------------------------------------------------
| Preserve Input
|--------------------------------------------------------------------------
*/

$old = [
    'name' => $name,
    'mailing_name' => $mailingName,
    'address' => $address,
    'state' => $state,
    'country' => $country,
    'pincode' => $pincode,
    'phone' => $phone,
    'email' => $email,
    'gstin' => $gstin,
];


/*
|--------------------------------------------------------------------------
| Validate Company ID
|--------------------------------------------------------------------------
*/

$errors = [];

if (!$companyId || $companyId <= 0) {

    $errors['general'] = 'Invalid company selected.';

}


/*
|--------------------------------------------------------------------------
| Validate Company Name
|--------------------------------------------------------------------------
*/

if ($name === '') {

    $errors['name'] = 'Company name is required.';

} elseif (mb_strlen($name) > 150) {

    $errors['name'] =
        'Company name cannot exceed 150 characters.';

}


/*
|--------------------------------------------------------------------------
| Validate Country
|--------------------------------------------------------------------------
*/

if ($country === '') {

    $errors['general'] =
        'Country is required.';

}


/*
|--------------------------------------------------------------------------
| Validate Email
|--------------------------------------------------------------------------
*/

if (
    $email !== '' &&
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {

    $errors['email'] =
        'Enter a valid email address.';

}


/*
|--------------------------------------------------------------------------
| Validate GSTIN
|--------------------------------------------------------------------------
*/

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
| Check Company Exists
|--------------------------------------------------------------------------
*/

if (empty($errors)) {

    $existingCompany = findCompany(
        $pdo,
        (int) $companyId
    );

    if (!$existingCompany) {

        $errors['general'] =
            'The selected company does not exist.';

    }

}


/*
|--------------------------------------------------------------------------
| Return On Validation Failure
|--------------------------------------------------------------------------
*/

if (!empty($errors)) {

    $_SESSION['company_alter_errors'] =
        $errors;

    $_SESSION['company_alter_old'] =
        $old;

    header(
        'Location: ' .
        BASE_URL .
        'company/alter.php?id=' .
        (int) $companyId
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Update Company
|--------------------------------------------------------------------------
*/

try {

    $success = updateCompany(
        $pdo,
        (int) $companyId,
        [
            'name' => $name,

            'mailing_name' =>
                $mailingName !== ''
                    ? $mailingName
                    : $name,

            'address' =>
                $address !== ''
                    ? $address
                    : null,

            'state' =>
                $state !== ''
                    ? $state
                    : null,

            'country' => $country,

            'pincode' =>
                $pincode !== ''
                    ? $pincode
                    : null,

            'phone' =>
                $phone !== ''
                    ? $phone
                    : null,

            'email' =>
                $email !== ''
                    ? $email
                    : null,

            'gstin' =>
                $gstin !== ''
                    ? $gstin
                    : null,
        ]
    );


    if (!$success) {

        throw new RuntimeException(
            'Company update failed.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Update Active Session If This Is The Active Company
    |--------------------------------------------------------------------------
    */

    if (
        isset($_SESSION['company_id']) &&
        (int) $_SESSION['company_id'] ===
        (int) $companyId
    ) {

        $_SESSION['company_name'] = $name;

    }


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

    $_SESSION['company_alter_errors'] = [
        'general' =>
            'Unable to update company. ' .
            $e->getMessage(),
    ];

    $_SESSION['company_alter_old'] =
        $old;

    header(
        'Location: ' .
        BASE_URL .
        'company/alter.php?id=' .
        (int) $companyId
    );

    exit;
}