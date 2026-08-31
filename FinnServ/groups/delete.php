<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';

// Require Active Company
if (!isset($_SESSION['company_id']) || (int)$_SESSION['company_id'] <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$companyId = (int)$_SESSION['company_id'];
$companyName = $_SESSION['company_name'] ?? 'No Company Selected';

$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
$groupId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;

$error = null;
$successMessage = $_SESSION['group_success'] ?? null;
unset($_SESSION['group_success']);

// POST Process: Deletion Execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;

    if ($postId > 0) {
        $stmt = $pdo->prepare("SELECT id, name, is_system FROM groups WHERE id = ? AND company_id = ? LIMIT 1");
        $stmt->execute([$postId, $companyId]);
        $groupToDelete = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($groupToDelete) {
            if ((int)$groupToDelete['is_system'] === 1) {
                $error = 'System groups cannot be deleted.';
            } else {
                // Dependency checks
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM groups WHERE parent_id = ?");
                $stmt->execute([$postId]);
                $hasChildren = ((int)$stmt->fetchColumn()) > 0;

                $stmt = $pdo->prepare("SELECT COUNT(*) FROM ledgers WHERE group_id = ?");
                $stmt->execute([$postId]);
                $hasLedgers = ((int)$stmt->fetchColumn()) > 0;

                if ($hasChildren) {
                    $error = 'This group contains child groups and cannot be deleted.';
                } elseif ($hasLedgers) {
                    $error = 'This group is being used by one or more ledgers/accounts and cannot be deleted.';
                } else {
                    try {
                        $pdo->beginTransaction();
                        $stmt = $pdo->prepare("DELETE FROM groups WHERE id = ? AND company_id = ?");
                        $stmt->execute([$postId, $companyId]);
                        $pdo->commit();

                        $_SESSION['group_success'] = "Group deleted successfully.";
                        header('Location: ' . BASE_URL . 'groups/delete.php');
                        exit;
                    } catch (Exception $e) {
                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                        }
                        $error = 'Failed to delete group. Please try again.';
                    }
                }
            }
        } else {
            $error = 'Group not found.';
        }
    } else {
        $error = 'Invalid group selected.';
    }
}

// GET Process: List user-created groups for deletion
$stmt = $pdo->prepare("SELECT id, name FROM groups WHERE company_id = ? AND is_system = 0 ORDER BY name ASC");
$stmt->execute([$companyId]);
$userGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Group | FinnServ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/company.css">
</head>
<body class="gateway-page">
<div class="gateway-app">

    <!-- TOP BAR -->
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

    <!-- TITLE BAR -->
    <div class="gateway-titlebar">
        <strong>Delete Group</strong>
        <div class="gateway-title-company"><?= htmlspecialchars($companyName) ?></div>
    </div>

    <!-- SELECTION / CONFIRMATION PANEL -->
    <main class="company-select-page">
        <section class="company-select-panel">
            <?php if ($error || ($_SESSION['group_error'] ?? null)): ?>
                <?php
                $dispError = $error ?: $_SESSION['group_error'];
                unset($_SESSION['group_error']);
                ?>
                <div class="form-error" style="margin: 10px 10px 0;">
                    <?= htmlspecialchars($dispError) ?>
                </div>
            <?php endif; ?>

            <?php if ($successMessage): ?>
                <div class="company-select-empty" style="color: #166534; font-weight: bold; background: #f0fdf4; border-bottom: 1px solid #bbf7d0;">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <?php
            $showList = true;
            if ($action === 'confirm' && $groupId > 0):
                $stmt = $pdo->prepare("SELECT id, name, is_system FROM groups WHERE id = ? AND company_id = ? LIMIT 1");
                $stmt->execute([$groupId, $companyId]);
                $groupToConfirm = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($groupToConfirm):
                    $showList = false;

                    // Dependency status checking for warning UI
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM groups WHERE parent_id = ?");
                    $stmt->execute([$groupId]);
                    $hasChildren = ((int)$stmt->fetchColumn()) > 0;

                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM ledgers WHERE group_id = ?");
                    $stmt->execute([$groupId]);
                    $hasLedgers = ((int)$stmt->fetchColumn()) > 0;
            ?>
                <div class="company-select-title" style="background: #dc2626;">Confirm Deletion</div>
                <div style="padding: 20px; background: #ffffff; color: #111827; font-size: 14px; line-height: 1.5;">
                    <p style="margin-bottom: 15px;">Are you sure you want to delete the group <strong><?= htmlspecialchars($groupToConfirm['name']) ?></strong>?</p>
                    
                    <?php if ((int)$groupToConfirm['is_system'] === 1): ?>
                        <p style="color: #dc2626; font-weight: bold; margin-bottom: 20px;">⚠️ Error: System groups cannot be deleted.</p>
                        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                            <a href="<?= BASE_URL ?>groups/delete.php" class="company-action-button">Back</a>
                        </div>
                    <?php elseif ($hasChildren): ?>
                        <p style="color: #dc2626; font-weight: bold; margin-bottom: 20px;">⚠️ Error: This group contains child groups and cannot be deleted.</p>
                        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                            <a href="<?= BASE_URL ?>groups/delete.php" class="company-action-button">Back</a>
                        </div>
                    <?php elseif ($hasLedgers): ?>
                        <p style="color: #dc2626; font-weight: bold; margin-bottom: 20px;">⚠️ Error: This group is being used by one or more ledgers/accounts and cannot be deleted.</p>
                        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                            <a href="<?= BASE_URL ?>groups/delete.php" class="company-action-button">Back</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?= BASE_URL ?>groups/delete.php">
                            <input type="hidden" name="id" value="<?= (int)$groupToConfirm['id'] ?>">
                            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                                <button type="submit" class="company-action-button primary" style="background: #dc2626; border-color: #b91c1c;">Yes, Delete Group</button>
                                <a href="<?= BASE_URL ?>groups/delete.php" class="company-action-button">Cancel</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php
                else:
                    echo '<div class="form-error" style="margin: 10px 10px 0;">Group not found.</div>';
                endif;
            endif;
            ?>

            <?php if ($showList): ?>
                <div class="company-select-title">Select Group to Delete</div>
                <div class="company-select-list">
                    <?php if (empty($userGroups)): ?>
                        <div class="company-select-empty">No user-created groups available for deletion.</div>
                    <?php endif; ?>
                    <?php foreach ($userGroups as $group): ?>
                        <a href="<?= BASE_URL ?>groups/delete.php?action=confirm&id=<?= (int)$group['id'] ?>" class="company-select-item" style="transition: background 0.2s;">
                            <span><?= htmlspecialchars($group['name']) ?></span>
                            <span style="color: #dc2626; font-size: 11px; font-weight: bold;">[DELETE]</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="company-select-actions">
                    <a href="<?= BASE_URL ?>groups/" class="company-action-button">Back</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="gateway-footer">
        <span>FinnServ v1.0.0</span>
        <span>Accounting &amp; Business Management System</span>
    </footer>

</div>
<script src="<?= BASE_URL ?>assets/js/gateway.js"></script>
</body>
</html>
