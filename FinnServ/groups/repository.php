<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Group Repository
|--------------------------------------------------------------------------
|
| All database queries related to Groups live here.
|
*/


/**
 * Get all groups belonging to a company.
 *
 * @return array<int, array<string, mixed>>
 */
function getAllGroups(PDO $pdo, int $companyId): array
{
    $stmt = $pdo->prepare("
        SELECT
            id,
            company_id,
            name,
            alias,
            parent_id,
            nature,
            affects_gross_profit,
            behaves_like_subledger,
            net_debit_credit_reporting,
            used_for_calculation,
            allocation_method,
            hsn_sac_details_mode,
            hsn_sac,
            hsn_sac_description,
            gst_rate_details_mode,
            taxability_type,
            gst_rate,
            is_system,
            display_order,
            created_at,
            updated_at
        FROM groups
        WHERE company_id = ?
        ORDER BY display_order ASC, name ASC
    ");

    $stmt->execute([$companyId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}