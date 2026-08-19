<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure active company context is selected
if (!isset($_SESSION['company_id']) || (int)$_SESSION['company_id'] <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$companyName = $_SESSION['company_name'] ?? 'Company';

$errors = $_SESSION['financial_year_errors'] ?? [];
$old = $_SESSION['financial_year_old'] ?? [];
unset($_SESSION['financial_year_errors'], $_SESSION['financial_year_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Financial Year | FinnServ</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/company.css">
</head>
<body class="gateway-page">
<div class="gateway-app">
    
    <!-- Topbar navigation header -->
    <header class="gateway-topbar">
        <div class="gateway-brand">
            <a href="<?= BASE_URL ?>dashboard/" class="gateway-logo-link">
                <div class="gateway-logo">FinnServ</div>
            </a>
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
        
        <a href="<?= BASE_URL ?>auth/logout.php" class="gateway-logout">Logout</a>
    </header>

    <!-- Page Titlebar -->
    <div class="gateway-titlebar">
        <strong>Create Financial Year</strong>
        <div class="gateway-title-company"><?= htmlspecialchars($companyName) ?></div>
    </div>

    <!-- Main Form page content -->
    <main class="company-form-page">
        <?php if (isset($errors['general'])): ?>
            <div class="form-error"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>financial-year/save.php" method="POST" class="company-form">
            
            <!-- Financial Year Name -->
            <div class="company-form-row">
                <label for="name">Financial Year</label>
                <input type="text" id="name" name="name" placeholder="e.g. 2027-28" maxlength="20" value="<?= htmlspecialchars($old['name'] ?? '') ?>" autofocus required>
                <?php if (isset($errors['name'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['name']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Start Date -->
            <div class="company-form-row">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($old['start_date'] ?? '') ?>" required>
                <?php if (isset($errors['start_date'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['start_date']) ?></small>
                <?php endif; ?>
            </div>

            <!-- End Date -->
            <div class="company-form-row">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($old['end_date'] ?? '') ?>" required>
                <?php if (isset($errors['end_date'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['end_date']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="company-form-actions">
                <a href="<?= BASE_URL ?>financial-year/" class="company-action-button">Quit</a>
                <button type="submit" class="company-action-button primary">Accept</button>
            </div>
        </form>
    </main>

    <!-- Footer block -->
    <footer class="gateway-footer">
        <span>FinnServ v1.0.0</span>
        <span>Accounting &amp; Business Management System</span>
    </footer>
</div>
<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>
</html>