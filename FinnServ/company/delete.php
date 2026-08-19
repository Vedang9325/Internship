<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';

$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
$companyId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$activeCompanyId = (int)($_SESSION['company_id'] ?? 0);

$error = null;
$successMessage = $_SESSION['company_delete_success'] ?? null;
unset($_SESSION['company_delete_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
    $postId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($postAction === 'delete' && $postId > 0) {
        $companyToDelete = findCompany($pdo, (int)$postId);
        if ($companyToDelete) {
            try {
                $pdo->beginTransaction();
                
                $stmt = $pdo->prepare("DELETE FROM companies WHERE id = ?");
                $stmt->execute([$postId]);
                
                $pdo->commit();

                // If the deleted company was active, clear the session variables
                if ($postId === $activeCompanyId) {
                    unset($_SESSION['company_id']);
                    unset($_SESSION['company_name']);
                    unset($_SESSION['financial_year_id']);
                    unset($_SESSION['financial_year_name']);
                    unset($_SESSION['financial_year_start']);
                    unset($_SESSION['financial_year_end']);
                }

                $_SESSION['company_delete_success'] = "Company '" . $companyToDelete['name'] . "' has been deleted successfully.";
                header('Location: ' . BASE_URL . 'company/delete.php');
                exit;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = "Failed to delete company: " . $e->getMessage();
            }
        } else {
            $error = "Company not found.";
        }
    }
}

$companies = getAllCompanies($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Company | FinnServ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/company.css">
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

    <div class="gateway-titlebar">
        <strong>Delete Company</strong>
        <div class="gateway-title-company"><?= htmlspecialchars($_SESSION['company_name'] ?? 'No Company Selected') ?></div>
    </div>

    <main class="company-select-page">
        <section class="company-select-panel">
            <?php if ($error): ?>
                <div class="form-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="company-select-empty" style="color: #166534; font-weight: bold; background: #f0fdf4; border-bottom: 1px solid #bbf7d0;">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <?php 
            $showList = true;
            if ($action === 'confirm' && $companyId > 0): 
                $companyToConfirm = findCompany($pdo, (int)$companyId);
                if ($companyToConfirm):
                    $showList = false;
            ?>
                <div class="company-select-title" style="background: #dc2626;">Confirm Deletion</div>
                <div style="padding: 20px; background: #ffffff; color: #111827; font-size: 14px; line-height: 1.5;">
                    <p style="margin-bottom: 15px;">Are you sure you want to delete the company <strong><?= htmlspecialchars($companyToConfirm['name']) ?></strong>?</p>
                    <p style="color: #dc2626; font-weight: bold; margin-bottom: 20px;">
                        ⚠️ WARNING: This action is permanent. All transactions, financial years, ledgers, vouchers, and users associated with this company will be permanently deleted.
                    </p>
                    <form method="POST" action="<?= BASE_URL ?>company/delete.php">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$companyToConfirm['id'] ?>">
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <button type="submit" class="company-action-button primary" style="background: #dc2626; border-color: #b91c1c;">Yes, Delete Company</button>
                            <a href="<?= BASE_URL ?>company/delete.php" class="company-action-button">Cancel</a>
                        </div>
                    </form>
                </div>
            <?php 
                else:
                    $error = "Company not found.";
                endif;
            endif; 
            ?>

            <?php if ($showList): ?>
                <div class="company-select-title">Delete Company</div>
                <div class="company-select-list">
                    <?php if (empty($companies)): ?>
                        <div class="company-select-empty">No companies available.</div>
                    <?php endif; ?>
                    <?php foreach ($companies as $company): ?>
                        <a href="<?= BASE_URL ?>company/delete.php?action=confirm&id=<?= (int)$company['id'] ?>" class="company-select-item" style="transition: background 0.2s;">
                            <span><?= htmlspecialchars($company['name']) ?></span>
                            <span style="color: #dc2626; font-size: 12px; font-weight: bold;">[DELETE]</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="company-select-actions">
                    <a href="<?= BASE_URL ?>company/menu.php" class="company-action-button">Back</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="gateway-footer">
        <span>FinnServ v1.0.0</span>
        <span>Accounting &amp; Business Management System</span>
    </footer>
</div>
</body>
</html>
