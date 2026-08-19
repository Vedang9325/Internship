<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/validator.php';


/*
|--------------------------------------------------------------------------
| Require Active Company
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['company_id']) ||
    (int) $_SESSION['company_id'] <= 0
) {
    header(
        'Location: ' .
        BASE_URL .
        'company/select.php'
    );

    exit;
}


$companyId = (int) $_SESSION['company_id'];


/*
|--------------------------------------------------------------------------
| Only POST Requests
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header(
        'Location: ' .
        BASE_URL .
        'groups/create.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Collect Form Data
|--------------------------------------------------------------------------
*/

$data = [

    'name' =>
        trim((string) ($_POST['name'] ?? '')),

    'alias' =>
        trim((string) ($_POST['alias'] ?? '')),

    'parent_id' =>
        $_POST['parent_id'] ?? '',

    'nature' =>
        (string) ($_POST['nature'] ?? ''),

    'affects_gross_profit' =>
        (string) (
            $_POST['affects_gross_profit']
            ?? '0'
        ),

    'behaves_like_subledger' =>
        (string) (
            $_POST['behaves_like_subledger']
            ?? '0'
        ),

    'net_debit_credit_reporting' =>
        (string) (
            $_POST['net_debit_credit_reporting']
            ?? '0'
        ),

    'used_for_calculation' =>
        (string) (
            $_POST['used_for_calculation']
            ?? '0'
        ),

    'allocation_method' =>
        (string) (
            $_POST['allocation_method']
            ?? 'Not Applicable'
        ),

    'hsn_sac_details_mode' =>
        (string) (
            $_POST['hsn_sac_details_mode']
            ?? 'As per Company/Group'
        ),

    'hsn_sac' =>
        trim(
            (string) (
                $_POST['hsn_sac']
                ?? ''
            )
        ),

    'hsn_sac_description' =>
        trim(
            (string) (
                $_POST['hsn_sac_description']
                ?? ''
            )
        ),

    'gst_rate_details_mode' =>
        (string) (
            $_POST['gst_rate_details_mode']
            ?? 'As per Company/Group'
        ),

    'taxability_type' =>
        trim(
            (string) (
                $_POST['taxability_type']
                ?? ''
            )
        ),

    'gst_rate' =>
        trim(
            (string) (
                $_POST['gst_rate']
                ?? ''
            )
        )
];


/*
|--------------------------------------------------------------------------
| Validate
|--------------------------------------------------------------------------
*/

$errors = validateGroupData(
    $pdo,
    $companyId,
    $data
);


if (!empty($errors)) {

    $_SESSION['group_create_errors'] =
        $errors;

    $_SESSION['group_create_old'] =
        $data;

    header(
        'Location: ' .
        BASE_URL .
        'groups/create.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Prepare Database Values
|--------------------------------------------------------------------------
*/

$parentId =
    $data['parent_id'] !== ''
        ? (int) $data['parent_id']
        : null;


$affectsGrossProfit =
    (int) $data['affects_gross_profit'];


$behavesLikeSubledger =
    (int) $data['behaves_like_subledger'];


$netDebitCreditReporting =
    (int) $data['net_debit_credit_reporting'];


$usedForCalculation =
    (int) $data['used_for_calculation'];


$gstRate =
    $data['gst_rate'] !== ''
        ? (float) $data['gst_rate']
        : null;


/*
|--------------------------------------------------------------------------
| Insert Group
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->prepare("
        INSERT INTO groups
        (
            company_id,
            name,
            alias,
            parent_id,
            nature,
            affects_gross_profit,
            behaves_like_subledger,
            net_debit_credit_reporting,
            used_for_calculation,
            allocation_method,
            hsn_sac_details_mode,
            hsn_sac,
            hsn_sac_description,
            gst_rate_details_mode,
            taxability_type,
            gst_rate,
            is_system,
            display_order
        )
        VALUES
        (
            :company_id,
            :name,
            :alias,
            :parent_id,
            :nature,
            :affects_gross_profit,
            :behaves_like_subledger,
            :net_debit_credit_reporting,
            :used_for_calculation,
            :allocation_method,
            :hsn_sac_details_mode,
            :hsn_sac,
            :hsn_sac_description,
            :gst_rate_details_mode,
            :taxability_type,
            :gst_rate,
            0,
            9999
        )
    ");


    $stmt->execute([

        ':company_id' =>
            $companyId,

        ':name' =>
            $data['name'],

        ':alias' =>
            $data['alias'] !== ''
                ? $data['alias']
                : null,

        ':parent_id' =>
            $parentId,

        ':nature' =>
            $data['nature'],

        ':affects_gross_profit' =>
            $affectsGrossProfit,

        ':behaves_like_subledger' =>
            $behavesLikeSubledger,

        ':net_debit_credit_reporting' =>
            $netDebitCreditReporting,

        ':used_for_calculation' =>
            $usedForCalculation,

        ':allocation_method' =>
            $data['allocation_method'],

        ':hsn_sac_details_mode' =>
            $data['hsn_sac_details_mode'],

        ':hsn_sac' =>
            $data['hsn_sac'] !== ''
                ? $data['hsn_sac']
                : null,

        ':hsn_sac_description' =>
            $data['hsn_sac_description'] !== ''
                ? $data['hsn_sac_description']
                : null,

        ':gst_rate_details_mode' =>
            $data['gst_rate_details_mode'],

        ':taxability_type' =>
            $data['taxability_type'] !== ''
                ? $data['taxability_type']
                : null,

        ':gst_rate' =>
            $gstRate
    ]);


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    $_SESSION['group_create_success'] =
        'Group created successfully.';


    header(
        'Location: ' .
        BASE_URL .
        'groups/'
    );

    exit;


} catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | Database Error
    |--------------------------------------------------------------------------
    */

    $_SESSION['group_create_errors'] = [

        'general' =>
            'Unable to create the group. Please try again.'

    ];

    $_SESSION['group_create_old'] =
        $data;


    header(
        'Location: ' .
        BASE_URL .
        'groups/create.php'
    );

    exit;
}