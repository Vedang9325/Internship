<?php
declare(strict_types=1);

/**
 * Validate Group Data
 *
 * Returns an associative array of validation errors.
 * An empty array means the data is valid.
 */
function validateGroupData(PDO $pdo, int $companyId, array $data, ?int $groupId = null): array
{
    $errors = [];

    // Name Validation
    $name = trim((string)($data['name'] ?? ''));
    if ($name === '') {
        $errors['name'] = 'Group name is required.';
    } elseif (mb_strlen($name) > 100) {
        $errors['name'] = 'Group name cannot exceed 100 characters.';
    } else {
        // Duplicate Name Check
        $sql = "SELECT id FROM groups WHERE company_id = ? AND LOWER(name) = LOWER(?)";
        $params = [$companyId, $name];

        if ($groupId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $groupId;
        }
        $sql .= " LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors['name'] = 'A group with this name already exists.';
        }
    }

    // Alias Validation
    $alias = trim((string)($data['alias'] ?? ''));
    if (mb_strlen($alias) > 100) {
        $errors['alias'] = 'Alias cannot exceed 100 characters.';
    }

    // Parent Group Validation
    $parentId = isset($data['parent_id']) && $data['parent_id'] !== '' ? (int)$data['parent_id'] : null;
    if ($parentId !== null) {
        // Parent must belong to same company
        $stmt = $pdo->prepare("SELECT id, is_system FROM groups WHERE id = ? AND company_id = ? LIMIT 1");
        $stmt->execute([$parentId, $companyId]);
        $parent = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$parent) {
            $errors['parent_id'] = 'Selected parent group is invalid.';
        }

        // Prevent a group from being its own parent
        if ($groupId !== null && $parentId === $groupId) {
            $errors['parent_id'] = 'A group cannot be its own parent.';
        }
    }

    // Nature Validation
    $allowedNatures = ['Assets', 'Liabilities', 'Income', 'Expenses'];
    $nature = (string)($data['nature'] ?? '');
    if (!in_array($nature, $allowedNatures, true)) {
        $errors['nature'] = 'Invalid group nature.';
    }

    // Boolean Settings Validation
    $booleanFields = ['affects_gross_profit', 'behaves_like_subledger', 'net_debit_credit_reporting', 'used_for_calculation'];
    foreach ($booleanFields as $field) {
        if (isset($data[$field]) && !in_array((string)$data[$field], ['0', '1'], true)) {
            $errors[$field] = 'Invalid value.';
        }
    }

    // Allocation Method Validation
    $allowedAllocationMethods = ['Not Applicable', 'Appropriate by Qty', 'Appropriate by Value'];
    $allocationMethod = (string)($data['allocation_method'] ?? 'Not Applicable');
    if (!in_array($allocationMethod, $allowedAllocationMethods, true)) {
        $errors['allocation_method'] = 'Invalid allocation method.';
    }

    // HSN/SAC Details Mode Validation
    $allowedHsnModes = ['As per Company/Group', 'Specify Details Here', 'Use GST Classification'];
    $hsnMode = (string)($data['hsn_sac_details_mode'] ?? 'As per Company/Group');
    if (!in_array($hsnMode, $allowedHsnModes, true)) {
        $errors['hsn_sac_details_mode'] = 'Invalid HSN/SAC details mode.';
    }

    // HSN/SAC Validation
    $hsnSac = trim((string)($data['hsn_sac'] ?? ''));
    if (mb_strlen($hsnSac) > 20) {
        $errors['hsn_sac'] = 'HSN/SAC cannot exceed 20 characters.';
    }

    // HSN/SAC Description Validation
    $description = trim((string)($data['hsn_sac_description'] ?? ''));
    if (mb_strlen($description) > 255) {
        $errors['hsn_sac_description'] = 'Description cannot exceed 255 characters.';
    }

    // GST Rate Details Mode Validation
    $allowedGstModes = ['As per Company/Group', 'Specify Details Here', 'Specify Slab-Based Rates', 'Use GST Classification'];
    $gstMode = (string)($data['gst_rate_details_mode'] ?? 'As per Company/Group');
    if (!in_array($gstMode, $allowedGstModes, true)) {
        $errors['gst_rate_details_mode'] = 'Invalid GST rate details mode.';
    }

    // Taxability Type Validation
    $taxabilityType = trim((string)($data['taxability_type'] ?? ''));
    if (mb_strlen($taxabilityType) > 50) {
        $errors['taxability_type'] = 'Taxability type cannot exceed 50 characters.';
    }

    // GST Rate Validation
    $gstRate = trim((string)($data['gst_rate'] ?? ''));
    if ($gstRate !== '') {
        if (!is_numeric($gstRate)) {
            $errors['gst_rate'] = 'GST rate must be numeric.';
        } else {
            $gstRateValue = (float)$gstRate;
            if ($gstRateValue < 0 || $gstRateValue > 100) {
                $errors['gst_rate'] = 'GST rate must be between 0 and 100.';
            }
        }
    }

    return $errors;
}