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

// Validate Group ID
$groupId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($groupId <= 0) {
    header('Location: ' . BASE_URL . 'groups/alter.php');
    exit;
}

// Load Selected Group
$stmt = $pdo->prepare("
    SELECT id, company_id, name, alias, parent_id, nature, affects_gross_profit, 
           behaves_like_subledger, net_debit_credit_reporting, used_for_calculation, 
           allocation_method, hsn_sac_details_mode, hsn_sac, hsn_sac_description, 
           gst_rate_details_mode, taxability_type, gst_rate, is_system, display_order
    FROM groups
    WHERE id = ? AND company_id = ?
    LIMIT 1
");
$stmt->execute([$groupId, $companyId]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    header('Location: ' . BASE_URL . 'groups/alter.php');
    exit;
}

$isSystemGroup = (int)$group['is_system'] === 1;

// Session Messages
$groupError = $_SESSION['group_error'] ?? null;
$groupSuccess = $_SESSION['group_success'] ?? null;
unset($_SESSION['group_error'], $_SESSION['group_success']);

// Load Parent Groups
$groups = getAllGroups($pdo, $companyId);
$parentGroups = [];
foreach ($groups as $possibleParent) {
    if ((int)$possibleParent['id'] === $groupId) {
        continue;
    }
    $parentGroups[] = $possibleParent;
}

// Display Values
$name = $group['name'];
$alias = $group['alias'] ?? '';
$parentId = $group['parent_id'] ?? '';
$nature = $group['nature'];
$affectsGrossProfit = (string)$group['affects_gross_profit'];
$behavesLikeSubledger = (string)$group['behaves_like_subledger'];
$netDebitCreditReporting = (string)$group['net_debit_credit_reporting'];
$usedForCalculation = (string)$group['used_for_calculation'];
$allocationMethod = $group['allocation_method'];
$hsnSacDetailsMode = $group['hsn_sac_details_mode'];
$hsnSac = $group['hsn_sac'] ?? '';
$hsnSacDescription = $group['hsn_sac_description'] ?? '';
$gstRateDetailsMode = $group['gst_rate_details_mode'];
$taxabilityType = $group['taxability_type'] ?? '';
$gstRate = $group['gst_rate'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alter Group | FinnServ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/gateway.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/company.css">
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
        <strong>Group Alteration</strong>
        <div class="gateway-title-company">
            <?= htmlspecialchars($companyName) ?>
        </div>
    </div>

    <!-- FORM -->
    <main class="company-form-page">
        <?php if ($groupSuccess): ?>
            <div class="form-success"><?= htmlspecialchars($groupSuccess) ?></div>
        <?php endif; ?>

        <?php if ($groupError): ?>
            <div class="form-error"><?= htmlspecialchars($groupError) ?></div>
        <?php endif; ?>

        <?php if ($isSystemGroup): ?>
            <div class="form-error">This is a system group. System groups cannot be modified.</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>groups/update.php" method="POST" class="company-form">
            <input type="hidden" name="id" value="<?= $groupId ?>">

            <!-- BASIC DETAILS -->
            <div class="company-form-row">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" maxlength="100" value="<?= htmlspecialchars($name) ?>" <?= $isSystemGroup ? 'readonly' : '' ?>>
            </div>

            <div class="company-form-row">
                <label for="alias">Alias</label>
                <input type="text" id="alias" name="alias" maxlength="100" value="<?= htmlspecialchars($alias) ?>" <?= $isSystemGroup ? 'readonly' : '' ?>>
            </div>

            <div class="company-form-row">
                <label for="parent_id">Under</label>
                <select id="parent_id" name="parent_id" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <option value="">Primary</option>
                    <?php foreach ($parentGroups as $parent): ?>
                        <option value="<?= (int)$parent['id'] ?>" <?= (string)$parentId === (string)$parent['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($parent['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="company-form-row">
                <label for="nature">Nature of Group</label>
                <select id="nature" name="nature" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <?php $natures = ['Assets', 'Liabilities', 'Income', 'Expenses']; ?>
                    <?php foreach ($natures as $natureOption): ?>
                        <option value="<?= $natureOption ?>" <?= $nature === $natureOption ? 'selected' : '' ?>>
                            <?= $natureOption ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="company-form-divider"></div>

            <!-- ACCOUNTING BEHAVIOUR -->
            <div class="company-form-section-title">Accounting Behaviour</div>

            <div class="company-form-row">
                <label for="affects_gross_profit">Does it affect gross profits</label>
                <select id="affects_gross_profit" name="affects_gross_profit" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <option value="0" <?= $affectsGrossProfit === '0' ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= $affectsGrossProfit === '1' ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>

            <div class="company-form-row">
                <label for="behaves_like_subledger">Group behaves like a sub-ledger</label>
                <select id="behaves_like_subledger" name="behaves_like_subledger" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <option value="0" <?= $behavesLikeSubledger === '0' ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= $behavesLikeSubledger === '1' ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>

            <div class="company-form-row">
                <label for="net_debit_credit_reporting">Net Debit/Credit Balances for Reporting</label>
                <select id="net_debit_credit_reporting" name="net_debit_credit_reporting" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <option value="0" <?= $netDebitCreditReporting === '0' ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= $netDebitCreditReporting === '1' ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>

            <div class="company-form-row">
                <label for="used_for_calculation">Used for calculation</label>
                <select id="used_for_calculation" name="used_for_calculation" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <option value="0" <?= $usedForCalculation === '0' ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= $usedForCalculation === '1' ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>

            <div class="company-form-row">
                <label for="allocation_method">Method to allocate when used in purchase invoice</label>
                <select id="allocation_method" name="allocation_method" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <?php $allocationMethods = ['Not Applicable', 'Appropriate by Qty', 'Appropriate by Value']; ?>
                    <?php foreach ($allocationMethods as $allocationOption): ?>
                        <option value="<?= $allocationOption ?>" <?= $allocationMethod === $allocationOption ? 'selected' : '' ?>>
                            <?= $allocationOption ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="company-form-divider"></div>

            <!-- STATUTORY DETAILS -->
            <div class="company-form-section-title">Statutory Details</div>

            <div class="company-form-row">
                <label for="hsn_sac_details_mode">HSN/SAC &amp; Related Details</label>
                <select id="hsn_sac_details_mode" name="hsn_sac_details_mode" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <?php $hsnModes = ['As per Company/Group', 'Specify Details Here', 'Use GST Classification']; ?>
                    <?php foreach ($hsnModes as $option): ?>
                        <option value="<?= $option ?>" <?= $hsnSacDetailsMode === $option ? 'selected' : '' ?>>
                            <?= $option ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="company-form-row">
                <label for="hsn_sac">HSN/SAC</label>
                <input type="text" id="hsn_sac" name="hsn_sac" maxlength="20" value="<?= htmlspecialchars($hsnSac) ?>" <?= $isSystemGroup ? 'readonly' : '' ?>>
            </div>

            <div class="company-form-row company-form-row-large">
                <label for="hsn_sac_description">Description</label>
                <textarea id="hsn_sac_description" name="hsn_sac_description" rows="3" <?= $isSystemGroup ? 'readonly' : '' ?>><?= htmlspecialchars($hsnSacDescription) ?></textarea>
            </div>

            <div class="company-form-row">
                <label for="gst_rate_details_mode">GST Rate &amp; Related Details</label>
                <select id="gst_rate_details_mode" name="gst_rate_details_mode" <?= $isSystemGroup ? 'disabled' : '' ?>>
                    <?php $gstModes = ['As per Company/Group', 'Specify Details Here', 'Specify Slab-Based Rates', 'Use GST Classification']; ?>
                    <?php foreach ($gstModes as $option): ?>
                        <option value="<?= $option ?>" <?= $gstRateDetailsMode === $option ? 'selected' : '' ?>>
                            <?= $option ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="company-form-row">
                <label for="taxability_type">Taxability Type</label>
                <input type="text" id="taxability_type" name="taxability_type" maxlength="50" value="<?= htmlspecialchars($taxabilityType) ?>" <?= $isSystemGroup ? 'readonly' : '' ?>>
            </div>

            <div class="company-form-row">
                <label for="gst_rate">GST Rate (%)</label>
                <input type="number" id="gst_rate" name="gst_rate" min="0" max="100" step="0.01" value="<?= htmlspecialchars((string)$gstRate) ?>" <?= $isSystemGroup ? 'readonly' : '' ?>>
            </div>

            <!-- ACTIONS -->
            <div class="company-form-actions">
                <a href="<?= BASE_URL ?>groups/alter.php" class="company-action-button">Back</a>
                <?php if (!$isSystemGroup): ?>
                    <button type="submit" class="company-action-button primary">Accept</button>
                    <button type="submit" form="delete-group-form" class="company-action-button" style="background: #ef4444; color: #ffffff; border-color: #dc2626;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Delete</button>
                <?php endif; ?>
            </div>
        </form>

        <?php if (!$isSystemGroup): ?>
            <form id="delete-group-form" action="<?= BASE_URL ?>groups/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this group?');">
                <input type="hidden" name="id" value="<?= $groupId ?>">
            </form>
        <?php endif; ?>
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