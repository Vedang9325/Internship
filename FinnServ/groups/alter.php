<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';


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
| Load Groups
|--------------------------------------------------------------------------
*/

$groups = getAllGroups($pdo, $companyId);

$groupError = $_SESSION['group_error'] ?? null;
$groupSuccess = $_SESSION['group_success'] ?? null;
unset($_SESSION['group_error'], $_SESSION['group_success']);


/*
|--------------------------------------------------------------------------
| Sort Groups
|--------------------------------------------------------------------------
|
| System groups first, followed by user-created groups.
|
*/

usort(
    $groups,
    static function (array $a, array $b): int {

        if (
            (int) $a['is_system'] !==
            (int) $b['is_system']
        ) {
            return
                (int) $b['is_system'] <=>
                (int) $a['is_system'];
        }

        return strcasecmp(
            (string) $a['name'],
            (string) $b['name']
        );
    }
);

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
        Alter Group | FinnServ
    </title>

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/style.css?v=<?= time() ?>"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/gateway.css?v=<?= time() ?>"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/company.css?v=<?= time() ?>"
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
            Alter Group
        </strong>

        <div class="gateway-title-company">

            <?= htmlspecialchars($companyName) ?>

        </div>

    </div>



    <!-- =====================================================
         GROUP SELECTION
         ===================================================== -->

    <main class="company-select-page">

        <section class="company-select-panel">


            <div class="company-select-title">

                Select Group to Alter

            </div>

            <?php if ($groupSuccess): ?>
                <div class="form-success" style="margin: 10px 10px 0;">
                    <?= htmlspecialchars($groupSuccess) ?>
                </div>
            <?php endif; ?>

            <?php if ($groupError): ?>
                <div class="form-error" style="margin: 10px 10px 0;">
                    <?= htmlspecialchars($groupError) ?>
                </div>
            <?php endif; ?>


            <div class="company-select-list">


                <?php if (empty($groups)): ?>

                    <div class="company-select-empty">

                        No groups available.

                    </div>

                <?php else: ?>


                    <?php foreach ($groups as $group): ?>

                        <a
                            href="<?= BASE_URL ?>groups/edit.php?id=<?= (int) $group['id'] ?>"
                            class="company-select-item"
                        >

                            <span>

                                <?= htmlspecialchars(
                                    $group['name']
                                ) ?>

                            </span>


                            <?php if (
                                (int) $group['is_system'] === 1
                            ): ?>

                                <span class="company-active-label">

                                    SYSTEM

                                </span>

                            <?php else: ?>

                                <span>

                                    USER

                                </span>

                            <?php endif; ?>


                        </a>

                    <?php endforeach; ?>


                <?php endif; ?>


            </div>


            <div class="company-select-actions">

                <a
                    href="<?= BASE_URL ?>groups/"
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

<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>

</html>