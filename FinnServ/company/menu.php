<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';


/*
|--------------------------------------------------------------------------
| Active Company
|--------------------------------------------------------------------------
*/

$companyId = (int) (
    $_SESSION['company_id'] ?? 0
);

$companyName =
    $_SESSION['company_name'] ??
    'No Company Selected';

$hasActiveCompany = $companyId > 0;


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
        Change Company | FinnServ
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
            Change Company
        </strong>

        <div class="gateway-title-company">

            <?= htmlspecialchars($companyName) ?>

        </div>

    </div>



    <!-- =====================================================
         COMPANY MENU
         ===================================================== -->

    <main class="company-menu-page">

        <section class="company-menu-panel">


            <!-- MENU TITLE -->

            <div class="company-menu-title">

                Change Company

            </div>



            <!-- CURRENT COMPANY -->

            <div class="company-menu-current">

                <input
                    type="text"
                    value="<?= htmlspecialchars($companyName) ?>"
                    readonly
                >

            </div>



            <!-- COMPANY ACTIONS -->

            <div class="company-menu-list">


                <a
                    href="<?= BASE_URL ?>company/create.php"
                    class="company-menu-item"
                >
                    Create Company
                </a>


                <a
                    href="<?= BASE_URL ?>company/alter.php"
                    class="company-menu-item"
                >
                    Alter Company
                </a>


                <a
                    href="<?= BASE_URL ?>company/select.php"
                    class="company-menu-item"
                >
                    Select Company
                </a>


                <a
                    href="<?= BASE_URL ?>company/shut.php"
                    class="company-menu-item"
                >
                    Shut Company
                </a>


            </div>



            <!-- COMPANY STATUS -->

            <div class="company-menu-companies">


                <div class="company-menu-heading">

                    List of Companies

                </div>



                <?php if ($hasActiveCompany): ?>

                    <div class="company-menu-company-row active">

                        <span>

                            <?= htmlspecialchars(
                                $companyName
                            ) ?>

                        </span>


                        <span>

                            (ACTIVE)

                        </span>

                    </div>


                <?php else: ?>


                    <div class="company-menu-company-row">

                        <span>

                            No Company Selected

                        </span>

                    </div>


                <?php endif; ?>


            </div>



            <!-- FOOTER ACTION -->

            <div class="company-menu-footer">

                <a
                    href="<?= BASE_URL ?>dashboard/"
                    class="company-menu-back"
                >
                    ← Back to Gateway
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