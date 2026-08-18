<?php
declare(strict_types=1);

require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/context.php';

// Authentication guard
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit;
}

// Ensure active company context is selected
if (!isset($_SESSION['company_id']) || (int)$_SESSION['company_id'] <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

// Refresh company and financial year context variables on each load
loadCompanyContext($pdo, (int)$_SESSION['company_id']);

$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars(APP_NAME) ?></title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="app">
    
    <!-- Sidebar navigation layout -->
    <aside class="sidebar">
        <div class="brand">
            <a href="<?= BASE_URL ?>dashboard/" class="brand-link">
                <div class="brand-name">FinnServ</div>
            </a>
            <div class="brand-subtitle">Accounting & Business Management</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">MAIN</div>
            <a href="<?= BASE_URL ?>dashboard/" class="nav-link"><span>⌂</span>Dashboard</a>

            <div class="nav-section">MASTERS</div>
            <a href="<?= BASE_URL ?>company/" class="nav-link"><span>▣</span>Company</a>
            <a href="<?= BASE_URL ?>financial-year/" class="nav-link"><span>◷</span>Financial Years</a>
            <a href="#" class="nav-link"><span>◉</span>Ledgers</a>
            <a href="#" class="nav-link"><span>◉</span>Groups</a>
            <a href="#" class="nav-link"><span>◉</span>Stock Items</a>

            <div class="nav-section">ACCOUNTING</div>
            <a href="#" class="nav-link"><span>₹</span>Payment</a>
            <a href="#" class="nav-link"><span>₹</span>Receipt</a>
            <a href="#" class="nav-link"><span>↔</span>Contra</a>
            <a href="#" class="nav-link"><span>≡</span>Journal</a>

            <div class="nav-section">BUSINESS</div>
            <a href="#" class="nav-link"><span>▣</span>Sales</a>
            <a href="#" class="nav-link"><span>▣</span>Purchase</a>

            <div class="nav-section">REPORTS</div>
            <a href="#" class="nav-link"><span>▤</span>Reports</a>
            <a href="#" class="nav-link"><span>₹</span>GST</a>
        </nav>
    </aside>

    <!-- Main workspace area -->
    <div class="main-area">
        
        <!-- Header dashboard topbar -->
        <header class="topbar">
            <div>
                <div class="topbar-title"><?= htmlspecialchars($pageTitle) ?></div>
                <div class="topbar-company">
                    <?= htmlspecialchars($_SESSION['company_name'] ?? 'Company') ?> &nbsp; | &nbsp; FY: <?= htmlspecialchars($_SESSION['financial_year_name'] ?? 'N/A') ?>
                </div>
            </div>

            <div class="user-menu">
                <div class="user-info">
                    <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
                    <span><?= htmlspecialchars($_SESSION['role']) ?></span>
                </div>
                <a href="<?= BASE_URL ?>auth/logout.php" class="logout-button">Logout</a>
            </div>
        </header>

        <!-- Main content view injection -->
        <main class="content">
            <?php require_once __DIR__ . '/flash-display.php'; ?>