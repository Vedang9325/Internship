<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';
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

$companyName =
    $_SESSION['company_name'] ?? 'No Company Selected';


/*
|--------------------------------------------------------------------------
| Load Parent Groups
|--------------------------------------------------------------------------
*/

$groups = getAllGroups($pdo, $companyId);


/*
|--------------------------------------------------------------------------
| Previous Form Data
|--------------------------------------------------------------------------
*/

$old =
    $_SESSION['group_create_old'] ?? [];

$errors =
    $_SESSION['group_create_errors'] ?? [];


unset($_SESSION['group_create_old']);
unset($_SESSION['group_create_errors']);


/*
|--------------------------------------------------------------------------
| Defaults
|--------------------------------------------------------------------------
*/

$name =
    $old['name'] ?? '';

$alias =
    $old['alias'] ?? '';

$parentId =
    $old['parent_id'] ?? '';

$nature =
    $old['nature'] ?? 'Assets';

$affectsGrossProfit =
    $old['affects_gross_profit'] ?? '0';

$behavesLikeSubledger =
    $old['behaves_like_subledger'] ?? '0';

$netDebitCreditReporting =
    $old['net_debit_credit_reporting'] ?? '0';

$usedForCalculation =
    $old['used_for_calculation'] ?? '0';

$allocationMethod =
    $old['allocation_method']
    ?? 'Not Applicable';

$hsnSacDetailsMode =
    $old['hsn_sac_details_mode']
    ?? 'As per Company/Group';

$hsnSac =
    $old['hsn_sac'] ?? '';

$hsnSacDescription =
    $old['hsn_sac_description'] ?? '';

$gstRateDetailsMode =
    $old['gst_rate_details_mode']
    ?? 'As per Company/Group';

$taxabilityType =
    $old['taxability_type'] ?? '';

$gstRate =
    $old['gst_rate'] ?? '';


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Create Group | FinnServ
    </title>

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/gateway.css"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/company.css"
    >

</head>


<body class="gateway-page">

<div class="gateway-app">


    <!-- =====================================================
         TOP BAR
         ===================================================== -->

    <header class="gateway-topbar">

        <div class="gateway-brand">

            <div class="gateway-logo">
                FinnServ
            </div>

            <div class="gateway-version">
                Accounting &amp; Business Management
            </div>

        </div>


        <nav class="gateway-shortcuts">

            <a href="<?= BASE_URL ?>dashboard/">
                <u>K</u>: Company
            </a>

            <span>
                <u>Y</u>: Data
            </span>

            <span>
                <u>Z</u>: Exchange
            </span>

            <span>
                <u>G</u>: Go To
            </span>

            <span>
                <u>O</u>: Import
            </span>

            <span>
                <u>E</u>: Export
            </span>

            <span>
                <u>M</u>: Share
            </span>

            <span>
                <u>P</u>: Print
            </span>

        </nav>


        <a
            href="<?= BASE_URL ?>auth/logout.php"
            class="gateway-logout"
        >
            Logout
        </a>

    </header>



    <!-- =====================================================
         TITLE BAR
         ===================================================== -->

    <div class="gateway-titlebar">

        <strong>
            Group Creation
        </strong>

        <div class="gateway-title-company">

            <?= htmlspecialchars($companyName) ?>

        </div>

    </div>



    <!-- =====================================================
         FORM
         ===================================================== -->

    <main class="company-form-page">


        <form
            action="<?= BASE_URL ?>groups/save.php"
            method="POST"
            class="company-form"
        >


            <?php if (isset($errors['general'])): ?>

                <div class="form-error">

                    <?= htmlspecialchars(
                        $errors['general']
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 BASIC DETAILS
                 ================================================= -->

            <div class="company-form-row">

                <label for="name">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="100"
                    value="<?= htmlspecialchars($name) ?>"
                    autofocus
                    required
                >

                <?php if (isset($errors['name'])): ?>

                    <small class="field-error">

                        <?= htmlspecialchars(
                            $errors['name']
                        ) ?>

                    </small>

                <?php endif; ?>

            </div>


            <div class="company-form-row">

                <label for="alias">
                    Alias
                </label>

                <input
                    type="text"
                    id="alias"
                    name="alias"
                    maxlength="100"
                    value="<?= htmlspecialchars($alias) ?>"
                >

                <?php if (isset($errors['alias'])): ?>

                    <small class="field-error">

                        <?= htmlspecialchars(
                            $errors['alias']
                        ) ?>

                    </small>

                <?php endif; ?>

            </div>


            <div class="company-form-row">

                <label for="parent_id">
                    Under
                </label>

                <select
                    id="parent_id"
                    name="parent_id"
                >

                    <option value="">
                        Primary
                    </option>


                    <?php foreach ($groups as $group): ?>

                        <option
                            value="<?= (int) $group['id'] ?>"
                            <?= (string) $parentId ===
                                (string) $group['id']
                                    ? 'selected'
                                    : '' ?>
                        >

                            <?= htmlspecialchars(
                                $group['name']
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <?php if (isset($errors['parent_id'])): ?>

                    <small class="field-error">

                        <?= htmlspecialchars(
                            $errors['parent_id']
                        ) ?>

                    </small>

                <?php endif; ?>

            </div>


            <div class="company-form-row">

                <label for="nature">
                    Nature of Group
                </label>

                <select
                    id="nature"
                    name="nature"
                >

                    <option
                        value="Assets"
                        <?= $nature === 'Assets'
                            ? 'selected'
                            : '' ?>
                    >
                        Assets
                    </option>

                    <option
                        value="Liabilities"
                        <?= $nature === 'Liabilities'
                            ? 'selected'
                            : '' ?>
                    >
                        Liabilities
                    </option>

                    <option
                        value="Income"
                        <?= $nature === 'Income'
                            ? 'selected'
                            : '' ?>
                    >
                        Income
                    </option>

                    <option
                        value="Expenses"
                        <?= $nature === 'Expenses'
                            ? 'selected'
                            : '' ?>
                    >
                        Expenses
                    </option>

                </select>


                <?php if (isset($errors['nature'])): ?>

                    <small class="field-error">

                        <?= htmlspecialchars(
                            $errors['nature']
                        ) ?>

                    </small>

                <?php endif; ?>

            </div>


            <div class="company-form-divider"></div>



            <!-- =================================================
                 ACCOUNTING BEHAVIOUR
                 ================================================= -->

            <div class="company-form-section-title">

                Accounting Behaviour

            </div>


            <div class="company-form-row">

                <label for="affects_gross_profit">
                    Does it affect gross profits
                </label>

                <select
                    id="affects_gross_profit"
                    name="affects_gross_profit"
                >

                    <option
                        value="0"
                        <?= $affectsGrossProfit === '0'
                            ? 'selected'
                            : '' ?>
                    >
                        No
                    </option>

                    <option
                        value="1"
                        <?= $affectsGrossProfit === '1'
                            ? 'selected'
                            : '' ?>
                    >
                        Yes
                    </option>

                </select>

            </div>


            <div class="company-form-row">

                <label for="behaves_like_subledger">
                    Group behaves like a sub-ledger
                </label>

                <select
                    id="behaves_like_subledger"
                    name="behaves_like_subledger"
                >

                    <option
                        value="0"
                        <?= $behavesLikeSubledger === '0'
                            ? 'selected'
                            : '' ?>
                    >
                        No
                    </option>

                    <option
                        value="1"
                        <?= $behavesLikeSubledger === '1'
                            ? 'selected'
                            : '' ?>
                    >
                        Yes
                    </option>

                </select>

            </div>


            <div class="company-form-row">

                <label for="net_debit_credit_reporting">
                    Net Debit/Credit Balances for Reporting
                </label>

                <select
                    id="net_debit_credit_reporting"
                    name="net_debit_credit_reporting"
                >

                    <option
                        value="0"
                        <?= $netDebitCreditReporting === '0'
                            ? 'selected'
                            : '' ?>
                    >
                        No
                    </option>

                    <option
                        value="1"
                        <?= $netDebitCreditReporting === '1'
                            ? 'selected'
                            : '' ?>
                    >
                        Yes
                    </option>

                </select>

            </div>


            <div class="company-form-row">

                <label for="used_for_calculation">
                    Used for calculation
                </label>

                <select
                    id="used_for_calculation"
                    name="used_for_calculation"
                >

                    <option
                        value="0"
                        <?= $usedForCalculation === '0'
                            ? 'selected'
                            : '' ?>
                    >
                        No
                    </option>

                    <option
                        value="1"
                        <?= $usedForCalculation === '1'
                            ? 'selected'
                            : '' ?>
                    >
                        Yes
                    </option>

                </select>

            </div>


            <div class="company-form-row">

                <label for="allocation_method">
                    Method to allocate when used in purchase invoice
                </label>

                <select
                    id="allocation_method"
                    name="allocation_method"
                >

                    <option
                        value="Not Applicable"
                        <?= $allocationMethod ===
                            'Not Applicable'
                                ? 'selected'
                                : '' ?>
                    >
                        Not Applicable
                    </option>

                    <option
                        value="Appropriate by Qty"
                        <?= $allocationMethod ===
                            'Appropriate by Qty'
                                ? 'selected'
                                : '' ?>
                    >
                        Appropriate by Qty
                    </option>

                    <option
                        value="Appropriate by Value"
                        <?= $allocationMethod ===
                            'Appropriate by Value'
                                ? 'selected'
                                : '' ?>
                    >
                        Appropriate by Value
                    </option>

                </select>

            </div>


            <div class="company-form-divider"></div>



            <!-- =================================================
                 STATUTORY DETAILS
                 ================================================= -->

            <div class="company-form-section-title">

                Statutory Details

            </div>


            <!-- HSN/SAC -->

            <div class="company-form-row">

                <label for="hsn_sac_details_mode">
                    HSN/SAC &amp; Related Details
                </label>

                <select
                    id="hsn_sac_details_mode"
                    name="hsn_sac_details_mode"
                >

                    <option
                        value="As per Company/Group"
                        <?= $hsnSacDetailsMode ===
                            'As per Company/Group'
                                ? 'selected'
                                : '' ?>
                    >
                        As per Company/Group
                    </option>

                    <option
                        value="Specify Details Here"
                        <?= $hsnSacDetailsMode ===
                            'Specify Details Here'
                                ? 'selected'
                                : '' ?>
                    >
                        Specify Details Here
                    </option>

                    <option
                        value="Use GST Classification"
                        <?= $hsnSacDetailsMode ===
                            'Use GST Classification'
                                ? 'selected'
                                : '' ?>
                    >
                        Use GST Classification
                    </option>

                </select>

            </div>


            <div class="company-form-row">

                <label for="hsn_sac">
                    HSN/SAC
                </label>

                <input
                    type="text"
                    id="hsn_sac"
                    name="hsn_sac"
                    maxlength="20"
                    value="<?= htmlspecialchars($hsnSac) ?>"
                >

            </div>


            <div class="company-form-row company-form-row-large">

                <label for="hsn_sac_description">
                    Description
                </label>

                <textarea
                    id="hsn_sac_description"
                    name="hsn_sac_description"
                    rows="3"
                ><?= htmlspecialchars(
                    $hsnSacDescription
                ) ?></textarea>

            </div>


            <!-- GST -->

            <div class="company-form-row">

                <label for="gst_rate_details_mode">
                    GST Rate &amp; Related Details
                </label>

                <select
                    id="gst_rate_details_mode"
                    name="gst_rate_details_mode"
                >

                    <option
                        value="As per Company/Group"
                        <?= $gstRateDetailsMode ===
                            'As per Company/Group'
                                ? 'selected'
                                : '' ?>
                    >
                        As per Company/Group
                    </option>

                    <option
                        value="Specify Details Here"
                        <?= $gstRateDetailsMode ===
                            'Specify Details Here'
                                ? 'selected'
                                : '' ?>
                    >
                        Specify Details Here
                    </option>

                    <option
                        value="Specify Slab-Based Rates"
                        <?= $gstRateDetailsMode ===
                            'Specify Slab-Based Rates'
                                ? 'selected'
                                : '' ?>
                    >
                        Specify Slab-Based Rates
                    </option>

                    <option
                        value="Use GST Classification"
                        <?= $gstRateDetailsMode ===
                            'Use GST Classification'
                                ? 'selected'
                                : '' ?>
                    >
                        Use GST Classification
                    </option>

                </select>

            </div>


            <div class="company-form-row">

                <label for="taxability_type">
                    Taxability Type
                </label>

                <input
                    type="text"
                    id="taxability_type"
                    name="taxability_type"
                    maxlength="50"
                    value="<?= htmlspecialchars(
                        $taxabilityType
                    ) ?>"
                >

            </div>


            <div class="company-form-row">

                <label for="gst_rate">
                    GST Rate (%)
                </label>

                <input
                    type="number"
                    id="gst_rate"
                    name="gst_rate"
                    min="0"
                    max="100"
                    step="0.01"
                    value="<?= htmlspecialchars($gstRate) ?>"
                >

                <?php if (isset($errors['gst_rate'])): ?>

                    <small class="field-error">

                        <?= htmlspecialchars(
                            $errors['gst_rate']
                        ) ?>

                    </small>

                <?php endif; ?>

            </div>



            <!-- =================================================
                 ACTIONS
                 ================================================= -->

            <div class="company-form-actions">

                <a
                    href="<?= BASE_URL ?>groups/"
                    class="company-action-button"
                >
                    Quit
                </a>

                <button
                    type="submit"
                    class="company-action-button primary"
                >
                    Accept
                </button>

            </div>


        </form>

    </main>



    <!-- =====================================================
         FOOTER
         ===================================================== -->

    <footer class="gateway-footer">

        <span>
            FinnServ v1.0.0
        </span>

        <span>
            Accounting &amp; Business Management System
        </span>

    </footer>


</div>

<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>

</html>