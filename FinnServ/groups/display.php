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

// Load Groups
$groups = getAllGroups($pdo, $companyId);

// Build Parent → Children Map
$childrenByParent = [];
foreach ($groups as $group) {
    $parentId = $group['parent_id'];
    $parentKey = ($parentId === null) ? 'root' : (string)$parentId;
    if (!isset($childrenByParent[$parentKey])) {
        $childrenByParent[$parentKey] = [];
    }
    $childrenByParent[$parentKey][] = $group;
}

// Recursive Renderer
function renderGroupTree(array $childrenByParent, string $parentKey = 'root', int $level = 0): void {
    if (!isset($childrenByParent[$parentKey]) || empty($childrenByParent[$parentKey])) {
        return;
    }
    foreach ($childrenByParent[$parentKey] as $group) {
        $indent = $level > 0 ? str_repeat('│   ', $level - 1) . '└─ ' : '';
        ?>
        <div class="company-display-row">
            <span class="company-display-name"><?= htmlspecialchars($indent . $group['name']) ?></span>
            <span class="company-display-nature"><?= htmlspecialchars($group['nature']) ?></span>
        </div>
        <?php
        renderGroupTree($childrenByParent, (string)$group['id'], $level + 1);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Groups | FinnServ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/company.css?v=<?= time() ?>">
</head>
<body class="gateway-page">
<div class="gateway-app">

    <!-- TOP BAR -->
    <header class="gateway-topbar">
        <div class="gateway-brand">
            <div class="gateway-logo">FinnServ</div>
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
        <strong>Display Groups</strong>
        <div class="gateway-title-company">
            <?= htmlspecialchars($companyName) ?>
        </div>
    </div>

    <!-- GROUP DISPLAY -->
    <main class="company-menu-page">
        <section class="company-menu-panel">
            <div class="company-menu-title">Groups</div>
            <div class="company-display-list">
                <?php if (empty($groups)): ?>
                    <div class="company-select-empty">No groups found.</div>
                <?php else: ?>
                    <?php renderGroupTree($childrenByParent); ?>
                <?php endif; ?>
            </div>
            <div class="company-menu-footer">
                <a href="<?= BASE_URL ?>groups/" class="company-menu-back">← Back to Groups</a>
            </div>
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