<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';

// Ensure active company context is selected
if (!isset($_SESSION['company_id']) || (int)$_SESSION['company_id'] <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

// Fetch active company financial years
$companyId = (int)$_SESSION['company_id'];
$financialYears = getFinancialYears($pdo, $companyId);
$companyName = $_SESSION['company_name'] ?? 'Company';
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
        <strong>Financial Years</strong>
        <div class="gateway-title-company"><?= htmlspecialchars($companyName) ?></div>
    </div>

    <!-- Main Content Panel -->
    <main class="company-form-page">
        <?php 
        $flash = getFlash();
        if ($flash): ?>
            <div class="form-error" style="border-color: <?= $flash['type'] === 'danger' ? '#dc2626' : '#16a34a' ?>; background: <?= $flash['type'] === 'danger' ? '#fef2f2' : '#f0fdf4' ?>; color: <?= $flash['type'] === 'danger' ? '#b91c1c' : '#15803d' ?>; padding: 8px 12px; margin-bottom: 15px; border: 1px solid;">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Title header block -->
        <div class="page-header">
            <div>
                <h1>Financial Years</h1>
                <p>Manage accounting periods for your company.</p>
            </div>
            
            <a href="<?= BASE_URL ?>financial-year/create.php" class="btn btn-primary">+ New Financial Year</a>
        </div>

        <!-- Table Display Card -->
        <div class="card">
            <div class="card-header">
                <h2>Financial Years</h2>
                <span><?= count($financialYears) ?> period(s)</span>
            </div>

            <?php if (empty($financialYears)): ?>
                <p>No financial years have been created.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Financial Year</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                        <?php foreach ($financialYears as $financialYear): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($financialYear['name']) ?></strong></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($financialYear['start_date']))) ?></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($financialYear['end_date']))) ?></td>
                                <td>
                                    <?php if ((int)$financialYear['is_active'] === 1): ?>
                                        <span class="status-badge status-active">Active</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <div class="table-actions">
                                        <?php if ((int)$financialYear['is_active'] !== 1): ?>
                                            <a href="<?= BASE_URL ?>financial-year/activate.php?id=<?= (int)$financialYear['id'] ?>" class="action-link action-primary">Activate</a>
                                        <?php else: ?>
                                            <span class="action-link text-muted">Current Active</span>
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

    <!-- Footer block -->
    <footer class="gateway-footer">
        <span>FinnServ v1.0.0</span>
        <span>Accounting &amp; Business Management System</span>
    </footer>
</div>
<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>
</html>
