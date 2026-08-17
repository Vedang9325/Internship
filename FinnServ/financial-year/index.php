<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';

// Include repository helper queries (getFinancialYears).
require_once __DIR__ . '/repository.php';

$companyId = (int) $_SESSION['company_id'];

// Retrieve a list of all historical and future financial year periods configured for this company.
$financialYears = getFinancialYears(
    $pdo,
    $companyId
);

$companyName =
    $_SESSION['company_name'] ?? 'Company';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Years | FinnServ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/company.css">
</head>

<body class="gateway-page">

<div class="gateway-app">

    <header class="gateway-topbar">
        <div class="gateway-brand">
            <div class="gateway-logo">FinnServ</div>
            <div class="gateway-version">Accounting &amp; Business Management</div>
        </div>

        <nav class="gateway-shortcuts">
            <a href="<?= BASE_URL ?>company/menu.php"><u>K</u>: Company</a>
            <span><u>Y</u>: Data</span>
            <span><u>Z</u>: Exchange</span>
            <span><u>G</u>: Go To</span>
            <span><u>O</u>: Import</span>
            <span><u>E</u>: Export</span>
            <span><u>M</u>: Share</span>
            <span><u>P</u>: Print</span>
        </nav>

        <a href="<?= BASE_URL ?>auth/logout.php" class="gateway-logout">
            Logout
        </a>
    </header>

    <div class="gateway-titlebar">
        <strong>Financial Years</strong>
        <div class="gateway-title-company">
            <?= htmlspecialchars($companyName) ?>
        </div>
    </div>

    <main class="company-form-page">

        <?php require_once __DIR__ . '/../includes/flash-display.php'; ?>


<div class="page-header">

    <div>

        <h1>Financial Years</h1>

        <p>
            Manage accounting periods for your company.
        </p>

    </div>


    <a
        href="<?= BASE_URL ?>financial-year/create.php"
        class="btn btn-primary"
    >
        + New Financial Year
    </a>

</div>


<div class="card">

    <div class="card-header">

        <h2>
            Financial Years
        </h2>

        <span>
            <?= count($financialYears) ?> period(s)
        </span>

    </div>


    <?php if (empty($financialYears)): ?>

        <p>
            No financial years have been created.
        </p>

    <?php else: ?>

        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            Financial Year
                        </th>

                        <th>
                            Start Date
                        </th>

                        <th>
                            End Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php foreach ($financialYears as $financialYear): ?>

                    <tr>

                        <td>
                            <strong>
                                <?= htmlspecialchars(
                                    $financialYear['name']
                                ) ?>
                            </strong>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                date(
                                    'd M Y',
                                    strtotime(
                                        $financialYear['start_date']
                                    )
                                )
                            ) ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                date(
                                    'd M Y',
                                    strtotime(
                                        $financialYear['end_date']
                                    )
                                )
                            ) ?>
                        </td>


                        <td>

                            <?php if ((int) $financialYear['is_active'] === 1): ?>

                                <span class="status-badge status-active">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="status-badge status-inactive">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>

                            <div class="table-actions">

                                <a
                                    href="<?= BASE_URL ?>financial-year/edit.php?id=<?= (int) $financialYear['id'] ?>"
                                    class="action-link"
                                >
                                    Edit
                                </a>


                                <?php if ((int) $financialYear['is_active'] !== 1): ?>

                                    <a
                                        href="<?= BASE_URL ?>financial-year/activate.php?id=<?= (int) $financialYear['id'] ?>"
                                        class="action-link action-primary"
                                    >
                                        Activate
                                    </a>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>


    </main>

    <footer class="gateway-footer">
        <span>FinnServ v1.0.0</span>
        <span>Accounting &amp; Business Management System</span>
    </footer>

</div>

<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>

</body>

</html>
