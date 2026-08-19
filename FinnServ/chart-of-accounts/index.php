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


$companyName =
    $_SESSION['company_name'] ?? 'No Company Selected';


$pageTitle = 'Chart of Accounts';

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
        Chart of Accounts | FinnServ
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
            Chart of Accounts
        </strong>

        <div class="gateway-title-company">

            <?= htmlspecialchars($companyName) ?>

        </div>

    </div>



    <!-- =====================================================
         MAIN CONTENT
         ===================================================== -->

    <main class="company-menu-page">

        <section class="company-menu-panel">


            <div class="company-menu-title">

                Chart of Accounts

            </div>


            <div class="company-menu-list">


                <!-- GROUPS -->

                <a
                    href="<?= BASE_URL ?>groups/"
                    class="company-menu-item"
                >
                    Groups
                </a>


                <!-- LEDGERS -->

                <a
                    href="#"
                    class="company-menu-item disabled"
                >
                    Ledgers
                </a>


            </div>


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

<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>

</html>