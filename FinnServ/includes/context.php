<?php

declare(strict_types=1);

/**
 * Loads the active company name and its active financial year dates into session variables.
 * This runs on every private page load, matching Tally's behavior of showing the active
 * company and accounting period details.
 *
 * @param PDO $pdo The active database connection resource.
 * @param int $companyId The ID of the company to query context for.
 * @throws RuntimeException If company or active financial year information is missing.
 */
function loadCompanyContext(PDO $pdo, int $companyId): void
{
    // Step 1: Query the database to retrieve company details.
    $stmt = $pdo->prepare("
        SELECT
            id,
            name
        FROM companies
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$companyId]);

    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        throw new RuntimeException(
            'Company context could not be loaded.'
        );
    }


    // Step 2: Query the active financial year (is_active = 1) for this company.
    $stmt = $pdo->prepare("
        SELECT
            id,
            name,
            start_date,
            end_date
        FROM financial_years
        WHERE company_id = ?
          AND is_active = 1
        LIMIT 1
    ");

    $stmt->execute([$companyId]);

    $financialYear = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$financialYear) {
        throw new RuntimeException(
            'No active financial year exists for this company.'
        );
    }


    // Step 3: Populate session parameters to use across views (e.g., in headers and sidebars).
    $_SESSION['company_id'] =
        (int) $company['id'];

    $_SESSION['company_name'] =
        $company['name'];

    $_SESSION['financial_year_id'] =
        (int) $financialYear['id'];

    $_SESSION['financial_year_name'] =
        $financialYear['name'];

    $_SESSION['financial_year_start'] =
        $financialYear['start_date'];

    $_SESSION['financial_year_end'] =
        $financialYear['end_date'];
}