<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';


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
| Require POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header(
        'Location: ' .
        BASE_URL .
        'groups/alter.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Read Group ID
|--------------------------------------------------------------------------
*/

$groupId =
    isset($_POST['id'])
        ? (int) $_POST['id']
        : 0;


if ($groupId <= 0) {

    $_SESSION['group_error'] =
        'Invalid group selected.';

    header(
        'Location: ' .
        BASE_URL .
        'groups/alter.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Load Existing Group
|--------------------------------------------------------------------------
|
| The company_id condition prevents editing a group belonging
| to another company.
|
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        company_id,
        name,
        is_system
    FROM groups
    WHERE id = ?
      AND company_id = ?
    LIMIT 1
");

$stmt->execute([
    $groupId,
    $companyId
]);

$existingGroup =
    $stmt->fetch(PDO::FETCH_ASSOC);


if (!$existingGroup) {

    $_SESSION['group_error'] =
        'Group not found.';

    header(
        'Location: ' .
        BASE_URL .
        'groups/alter.php'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Protect System Groups
|--------------------------------------------------------------------------
*/

if (
    (int) $existingGroup['is_system'] === 1
) {

    $_SESSION['group_error'] =
        'System groups cannot be modified.';

    header(
        'Location: ' .
        BASE_URL .
        'groups/edit.php?id=' .
        $groupId
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Read Submitted Values
|--------------------------------------------------------------------------
*/

$name =
    trim(
        (string) (
            $_POST['name'] ?? ''
        )
    );

$alias =
    trim(
        (string) (
            $_POST['alias'] ?? ''
        )
    );

$parentIdRaw =
    $_POST['parent_id'] ?? '';

$nature =
    trim(
        (string) (
            $_POST['nature'] ?? ''
        )
    );


$affectsGrossProfit =
    isset($_POST['affects_gross_profit'])
        ? (int) $_POST['affects_gross_profit']
        : 0;

$behavesLikeSubledger =
    isset($_POST['behaves_like_subledger'])
        ? (int) $_POST['behaves_like_subledger']
        : 0;

$netDebitCreditReporting =
    isset($_POST['net_debit_credit_reporting'])
        ? (int) $_POST['net_debit_credit_reporting']
        : 0;

$usedForCalculation =
    isset($_POST['used_for_calculation'])
        ? (int) $_POST['used_for_calculation']
        : 0;


$allocationMethod =
    trim(
        (string) (
            $_POST['allocation_method'] ?? ''
        )
    );


$hsnSacDetailsMode =
    trim(
        (string) (
            $_POST['hsn_sac_details_mode'] ?? ''
        )
    );

$hsnSac =
    trim(
        (string) (
            $_POST['hsn_sac'] ?? ''
        )
    );

$hsnSacDescription =
    trim(
        (string) (
            $_POST['hsn_sac_description'] ?? ''
        )
    );


$gstRateDetailsMode =
    trim(
        (string) (
            $_POST['gst_rate_details_mode'] ?? ''
        )
    );

$taxabilityType =
    trim(
        (string) (
            $_POST['taxability_type'] ?? ''
        )
    );

$gstRateRaw =
    trim(
        (string) (
            $_POST['gst_rate'] ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Convert Parent ID
|--------------------------------------------------------------------------
*/

$parentId = null;

if ($parentIdRaw !== '') {

    $parentId = (int) $parentIdRaw;

    if ($parentId <= 0) {
        $parentId = null;
    }
}


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$errors = [];


/*
| Name
*/

if ($name === '') {

    $errors[] =
        'Group name is required.';

} elseif (mb_strlen($name) > 100) {

    $errors[] =
        'Group name cannot exceed 100 characters.';
}


/*
| Alias
*/

if (mb_strlen($alias) > 100) {

    $errors[] =
        'Alias cannot exceed 100 characters.';
}


/*
| Nature
*/

$allowedNatures = [
    'Assets',
    'Liabilities',
    'Income',
    'Expenses'
];

if (
    !in_array(
        $nature,
        $allowedNatures,
        true
    )
) {

    $errors[] =
        'Invalid group nature.';
}


/*
| Boolean fields
*/

$booleanFields = [
    $affectsGrossProfit,
    $behavesLikeSubledger,
    $netDebitCreditReporting,
    $usedForCalculation
];

foreach ($booleanFields as $value) {

    if ($value !== 0 && $value !== 1) {

        $errors[] =
            'Invalid accounting behaviour value.';

        break;
    }
}


/*
| Allocation method
*/

$allowedAllocationMethods = [
    'Not Applicable',
    'Appropriate by Qty',
    'Appropriate by Value'
];

if (
    !in_array(
        $allocationMethod,
        $allowedAllocationMethods,
        true
    )
) {

    $errors[] =
        'Invalid allocation method.';
}


/*
| HSN/SAC mode
*/

$allowedHsnModes = [
    'As per Company/Group',
    'Specify Details Here',
    'Use GST Classification'
];

if (
    !in_array(
        $hsnSacDetailsMode,
        $allowedHsnModes,
        true
    )
) {

    $errors[] =
        'Invalid HSN/SAC details mode.';
}


/*
| GST mode
*/

$allowedGstModes = [
    'As per Company/Group',
    'Specify Details Here',
    'Specify Slab-Based Rates',
    'Use GST Classification'
];

if (
    !in_array(
        $gstRateDetailsMode,
        $allowedGstModes,
        true
    )
) {

    $errors[] =
        'Invalid GST rate details mode.';
}


/*
| HSN/SAC
*/

if (mb_strlen($hsnSac) > 20) {

    $errors[] =
        'HSN/SAC cannot exceed 20 characters.';
}


/*
| Taxability
*/

if (mb_strlen($taxabilityType) > 50) {

    $errors[] =
        'Taxability type cannot exceed 50 characters.';
}


/*
| GST rate
*/

$gstRate = null;

if ($gstRateRaw !== '') {

    if (!is_numeric($gstRateRaw)) {

        $errors[] =
            'GST rate must be a valid number.';

    } else {

        $gstRate = (float) $gstRateRaw;

        if (
            $gstRate < 0 ||
            $gstRate > 100
        ) {

            $errors[] =
                'GST rate must be between 0 and 100.';
        }
    }
}


/*
|--------------------------------------------------------------------------
| Validate Parent
|--------------------------------------------------------------------------
*/

if ($parentId !== null) {

    /*
    | A group cannot be its own parent.
    */

    if ($parentId === $groupId) {

        $errors[] =
            'A group cannot be its own parent.';
    }


    /*
    | Parent must belong to the same company.
    */

    if ($parentId !== $groupId) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM groups
            WHERE id = ?
              AND company_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $parentId,
            $companyId
        ]);

        if (!$stmt->fetch()) {

            $errors[] =
                'Selected parent group is invalid.';
        }
    }
}


/*
|--------------------------------------------------------------------------
| Prevent Circular Hierarchy
|--------------------------------------------------------------------------
*/

if (
    $parentId !== null &&
    $parentId !== $groupId
) {

    $currentParentId =
        $parentId;

    $visited = [];

    while ($currentParentId !== null) {

        if (
            isset(
                $visited[$currentParentId]
            )
        ) {

            $errors[] =
                'Invalid circular group hierarchy.';

            break;
        }

        $visited[$currentParentId] = true;


        /*
        | If we eventually reach the group
        | being edited, the new parent would
        | create a cycle.
        */

        if (
            $currentParentId === $groupId
        ) {

            $errors[] =
                'A group cannot be moved under one of its own children.';

            break;
        }


        $stmt = $pdo->prepare("
            SELECT parent_id
            FROM groups
            WHERE id = ?
              AND company_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $currentParentId,
            $companyId
        ]);

        $parentRow =
            $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$parentRow) {
            break;
        }

        $currentParentId =
            $parentRow['parent_id'] !== null
                ? (int) $parentRow['parent_id']
                : null;
    }
}


/*
|--------------------------------------------------------------------------
| Prevent Duplicate Group Names
|--------------------------------------------------------------------------
*/

if ($name !== '') {

    $stmt = $pdo->prepare("
        SELECT id
        FROM groups
        WHERE company_id = ?
          AND name = ?
          AND id <> ?
        LIMIT 1
    ");

    $stmt->execute([
        $companyId,
        $name,
        $groupId
    ]);

    if ($stmt->fetch()) {

        $errors[] =
            'A group with this name already exists.';
    }
}


/*
|--------------------------------------------------------------------------
| Return To Edit Form On Validation Failure
|--------------------------------------------------------------------------
*/

if (!empty($errors)) {

    $_SESSION['group_error'] =
        implode(' ', $errors);

    header(
        'Location: ' .
        BASE_URL .
        'groups/edit.php?id=' .
        $groupId
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Update Group
|--------------------------------------------------------------------------
*/

try {

    $pdo->beginTransaction();


    $stmt = $pdo->prepare("
        UPDATE groups
        SET
            name = ?,
            alias = ?,
            parent_id = ?,
            nature = ?,
            affects_gross_profit = ?,
            behaves_like_subledger = ?,
            net_debit_credit_reporting = ?,
            used_for_calculation = ?,
            allocation_method = ?,
            hsn_sac_details_mode = ?,
            hsn_sac = ?,
            hsn_sac_description = ?,
            gst_rate_details_mode = ?,
            taxability_type = ?,
            gst_rate = ?
        WHERE id = ?
          AND company_id = ?
          AND is_system = 0
    ");

    $stmt->execute([
        $name,
        $alias !== ''
            ? $alias
            : null,
        $parentId,
        $nature,
        $affectsGrossProfit,
        $behavesLikeSubledger,
        $netDebitCreditReporting,
        $usedForCalculation,
        $allocationMethod,
        $hsnSacDetailsMode,
        $hsnSac !== ''
            ? $hsnSac
            : null,
        $hsnSacDescription !== ''
            ? $hsnSacDescription
            : null,
        $gstRateDetailsMode,
        $taxabilityType !== ''
            ? $taxabilityType
            : null,
        $gstRate,
        $groupId,
        $companyId
    ]);


    if ($stmt->rowCount() !== 1) {

        throw new RuntimeException(
            'Group could not be updated.'
        );
    }


    $pdo->commit();


    $_SESSION['group_success'] =
        'Group updated successfully.';


    header(
        'Location: ' .
        BASE_URL .
        'groups/edit.php?id=' .
        $groupId
    );

    exit;


} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    $_SESSION['group_error'] =
        'Unable to update group.';


    header(
        'Location: ' .
        BASE_URL .
        'groups/edit.php?id=' .
        $groupId
    );

    exit;
}