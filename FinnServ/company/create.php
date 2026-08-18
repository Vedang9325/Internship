<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Company Creation';

// Load errors and values from previous submission
$errors = $_SESSION['company_create_errors'] ?? [];
$old = $_SESSION['company_create_old'] ?? [];
unset($_SESSION['company_create_errors'], $_SESSION['company_create_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Creation | FinnServ</title>
    
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
            <a href="<?= BASE_URL ?>dashboard/"><u>K</u>: Company</a>
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
        <strong>Company Creation</strong>
        <div class="gateway-title-company">FinnServ</div>
    </div>

    <!-- Main Form page content -->
    <main class="company-form-page">
        <?php if (isset($errors['general'])): ?>
            <div class="form-error"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>company/save.php" method="POST" class="company-form">
            
            <!-- Company Name -->
            <div class="company-form-row">
                <label for="name">Company Name</label>
                <input type="text" id="name" name="name" maxlength="150" value="<?= htmlspecialchars($old['name'] ?? '') ?>" autofocus required>
                <?php if (isset($errors['name'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['name']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Mailing Name -->
            <div class="company-form-row">
                <label for="mailing_name">Mailing Name</label>
                <input type="text" id="mailing_name" name="mailing_name" maxlength="150" value="<?= htmlspecialchars($old['mailing_name'] ?? '') ?>">
            </div>

            <!-- Address -->
            <div class="company-form-row company-form-row-large">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
            </div>

            <div class="company-form-divider"></div>

            <!-- Financial Year -->
            <div class="company-form-row">
                <label for="financial_year">Financial Year</label>
                <input type="text" id="financial_year" name="financial_year" maxlength="20" placeholder="2026-27" value="<?= htmlspecialchars($old['financial_year'] ?? '') ?>" required>
                <?php if (isset($errors['financial_year'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['financial_year']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Start Date -->
            <div class="company-form-row">
                <label for="start_date">Financial Year Beginning</label>
                <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($old['start_date'] ?? date('Y-04-01')) ?>" required>
                <?php if (isset($errors['start_date'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['start_date']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Books Beginning -->
            <div class="company-form-row">
                <label for="books_beginning">Books Beginning From</label>
                <input type="date" id="books_beginning" name="books_beginning" value="<?= htmlspecialchars($old['books_beginning'] ?? date('Y-04-01')) ?>" required>
                <?php if (isset($errors['books_beginning'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['books_beginning']) ?></small>
                <?php endif; ?>
            </div>

            <div class="company-form-divider"></div>

            <!-- State -->
            <div class="company-form-row">
                <label for="state">State</label>
                <input type="text" id="state" name="state" maxlength="100" value="<?= htmlspecialchars($old['state'] ?? '') ?>">
            </div>

            <!-- Country -->
            <div class="company-form-row">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" maxlength="100" value="<?= htmlspecialchars($old['country'] ?? 'India') ?>" required>
            </div>

            <!-- Pincode -->
            <div class="company-form-row">
                <label for="pincode">Pincode</label>
                <input type="text" id="pincode" name="pincode" maxlength="10" value="<?= htmlspecialchars($old['pincode'] ?? '') ?>">
            </div>

            <!-- Telephone -->
            <div class="company-form-row">
                <label for="phone">Telephone</label>
                <input type="text" id="phone" name="phone" maxlength="20" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
            </div>

            <!-- Email -->
            <div class="company-form-row">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" maxlength="150" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <?php if (isset($errors['email'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['email']) ?></small>
                <?php endif; ?>
            </div>

            <!-- GSTIN -->
            <div class="company-form-row">
                <label for="gstin">GSTIN</label>
                <input type="text" id="gstin" name="gstin" maxlength="15" value="<?= htmlspecialchars($old['gstin'] ?? '') ?>">
                <?php if (isset($errors['gstin'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['gstin']) ?></small>
                <?php endif; ?>
            </div>

            <div class="company-form-divider"></div>

            <!-- Currency Settings -->
            <div class="company-form-row">
                <label>Base Currency Symbol</label>
                <input type="text" value="₹" readonly>
            </div>

            <div class="company-form-row">
                <label>Formal Name</label>
                <input type="text" value="INR" readonly>
            </div>

            <!-- Actions -->
            <div class="company-form-actions">
                <a href="<?= BASE_URL ?>company/menu.php" class="company-action-button">Quit</a>
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
</body>
</html>