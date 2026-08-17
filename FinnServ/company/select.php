<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';


/*
|--------------------------------------------------------------------------
| Load Companies
|--------------------------------------------------------------------------
*/

$companies = getAllCompanies($pdo);

$activeCompanyId =
    (int) ($_SESSION['company_id'] ?? 0);

$switchError =
    $_SESSION['company_switch_error'] ?? null;

unset($_SESSION['company_switch_error']);

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
        Select Company | FinnServ
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
            Select Company
        </strong>

        <div class="gateway-title-company">

            <?= htmlspecialchars(
                $_SESSION['company_name'] ?? 'No Company'
            ) ?>

        </div>

    </div>



    <!-- =====================================================
         COMPANY SELECTION
         ===================================================== -->

    <main class="company-select-page">


        <section class="company-select-panel">
            <?php if ($switchError): ?>

    <div class="form-error">

        <?= htmlspecialchars($switchError) ?>

    </div>

<?php endif; ?>

            <div class="company-select-title">

                Select Company

            </div>


            <div class="company-select-list">


                <?php if (empty($companies)): ?>

                    <div class="company-select-empty">

                        No companies available.

                    </div>

                <?php endif; ?>


                <?php foreach ($companies as $company): ?>

                    <a
                        href="<?= BASE_URL ?>company/switch.php?id=<?= (int) $company['id'] ?>"
                        class="company-select-item
                        <?= (int) $company['id'] === $activeCompanyId
                            ? 'active'
                            : '' ?>"
                    >

                        <span>

                            <?= htmlspecialchars(
                                $company['name']
                            ) ?>

                        </span>


                        <?php if (
                            (int) $company['id'] ===
                            $activeCompanyId
                        ): ?>

                            <span class="company-active-label">
                                ACTIVE
                            </span>

                        <?php endif; ?>

                    </a>

                <?php endforeach; ?>

            </div>


            <div class="company-select-actions">

                <a
                    href="<?= BASE_URL ?>company/create.php"
                    class="company-action-button"
                >
                    Create Company
                </a>


                <a
                    href="<?= BASE_URL ?>company/menu.php"
                    class="company-action-button"
                >
                    Back
                </a>

            </div>


        </section>

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

</body>

</html>