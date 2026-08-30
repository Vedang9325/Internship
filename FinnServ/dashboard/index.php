<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isset($_SESSION['company_id']) || (int)$_SESSION['company_id'] <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$companyName = $_SESSION['company_name'] ?? 'Company';
$financialYearName = $_SESSION['financial_year_name'] ?? 'N/A';
$financialYearStart = $_SESSION['financial_year_start'] ?? null;
$financialYearEnd = $_SESSION['financial_year_end'] ?? null;
$currentDate = date('l, d-M-Y');
$pageTitle = 'Gateway of FinnServ';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gateway of FinnServ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css">
</head>
<body class="gateway-page">
<div class="gateway-app">
    <header class="gateway-topbar">
        <div class="gateway-brand">
            <a href="<?= BASE_URL ?>dashboard/" class="gateway-logo-link">
                <div class="gateway-logo">FinnServ</div>
            </a>
            <div class="gateway-version">Accounting &amp; Business Management</div>
        </div>
        <nav class="gateway-shortcuts">
            <button type="button" data-shortcut="company"><u>K</u>: Company</button>
            <button type="button" data-shortcut="data"><u>Y</u>: Data</button>
            <button type="button" data-shortcut="exchange"><u>Z</u>: Exchange</button>
            <button type="button" data-shortcut="goto"><u>G</u>: Go To</button>
            <button type="button" data-shortcut="import"><u>O</u>: Import</button>
            <button type="button" data-shortcut="export"><u>E</u>: Export</button>
            <button type="button" data-shortcut="share"><u>M</u>: Share</button>
            <button type="button" data-shortcut="print"><u>P</u>: Print</button>
        </nav>
        <a href="<?= BASE_URL ?>auth/logout.php" class="gateway-logout">Logout</a>
    </header>

    <div class="gateway-titlebar">
        <strong>Gateway of FinnServ</strong>
        <div class="gateway-title-actions">
            <button type="button" data-shortcut="date">F2: Date</button>
            <button type="button" data-shortcut="company">F3: Company</button>
            <button type="button" data-shortcut="help">F1: Help</button>
        </div>
    </div>

    <main class="gateway-main">
        <section class="gateway-information">
            <div class="gateway-info-block">
                <div class="gateway-info-label">CURRENT PERIOD</div>
                <div class="gateway-info-value">
                    <?php if ($financialYearStart && $financialYearEnd): ?>
                        <?= htmlspecialchars(date('d-M-y', strtotime($financialYearStart))) ?> to <?= htmlspecialchars(date('d-M-y', strtotime($financialYearEnd))) ?>
                    <?php else: ?>
                        No Financial Year
                    <?php endif; ?>
                </div>
            </div>

            <div class="gateway-info-block gateway-date-block">
                <div class="gateway-info-label">CURRENT DATE</div>
                <div class="gateway-info-value"><?= htmlspecialchars($currentDate) ?></div>
            </div>

            <div class="gateway-company-row">
                <div>
                    <div class="gateway-info-label">NAME OF COMPANY</div>
                    <div class="gateway-company-name"><?= htmlspecialchars($companyName) ?></div>
                </div>
                <div class="gateway-entry-status">
                    <div class="gateway-info-label">DATE OF LAST ENTRY</div>
                    <div class="gateway-company-name">No Vouchers Entered</div>
                </div>
            </div>

            <div class="gateway-context-box">
                <div>
                    <span>Financial Year</span>
                    <strong><?= htmlspecialchars($financialYearName) ?></strong>
                </div>
                <div>
                    <span>User</span>
                    <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></strong>
                </div>
                <div>
                    <span>Role</span>
                    <strong><?= htmlspecialchars($_SESSION['role'] ?? 'User') ?></strong>
                </div>
            </div>
        </section>

        <aside class="gateway-menu">
            <div class="gateway-menu-heading">Gateway of FinnServ</div>
            <div class="gateway-section">
                <div class="gateway-section-title">MASTERS</div>
                <a href="<?= BASE_URL ?>company/menu.php" class="gateway-menu-item">Company</a>
                <a href="<?= BASE_URL ?>financial-year/" class="gateway-menu-item">Financial Years</a>
                <a href="<?= BASE_URL ?>chart-of-accounts/" class="gateway-menu-item">Chart of Accounts</a>
            </div>
            <div class="gateway-section">
                <div class="gateway-section-title">TRANSACTIONS</div>
                <a href="#" class="gateway-menu-item disabled">Vouchers</a>
                <a href="#" class="gateway-menu-item disabled">Day Book</a>
            </div>
            <div class="gateway-section">
                <div class="gateway-section-title">UTILITIES</div>
                <a href="#" class="gateway-menu-item disabled">Banking</a>
            </div>
            <div class="gateway-section">
                <div class="gateway-section-title">REPORTS</div>
                <a href="#" class="gateway-menu-item disabled">Balance Sheet</a>
                <a href="#" class="gateway-menu-item disabled">Profit &amp; Loss A/c</a>
                <a href="#" class="gateway-menu-item disabled">Stock Summary</a>
                <a href="#" class="gateway-menu-item disabled">Ratio Analysis</a>
                <a href="#" class="gateway-menu-item disabled">Dashboard</a>
            </div>
            <a href="<?= BASE_URL ?>auth/logout.php" class="gateway-quit">Quit</a>
        </aside>
    </main>

    <footer class="gateway-footer">
        <span>FinnServ v1.0.0</span>
        <span>Accounting &amp; Business Management System</span>
    </footer>
</div>
<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>
</html>
