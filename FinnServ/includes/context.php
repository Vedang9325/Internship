<?php
declare(strict_types=1);

// Loads active company details and its current active financial year into session.
function loadCompanyContext(PDO $pdo, int $companyId): void
{
    // Fetch company info
    $stmt = $pdo->prepare("SELECT id, name FROM companies WHERE id = ? LIMIT 1");
    $stmt->execute([$companyId]);
    $company = $stmt->fetch();

    if (!$company) {
        throw new RuntimeException('Company context could not be loaded.');
    }

    // Fetch active financial year for this company
    $stmt = $pdo->prepare("SELECT id, name, start_date, end_date FROM financial_years WHERE company_id = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$companyId]);
    $financialYear = $stmt->fetch();

    if (!$financialYear) {
        throw new RuntimeException('No active financial year exists for this company.');
    }

    // Store context variables in session
    $_SESSION['company_id'] = (int) $company['id'];
    $_SESSION['company_name'] = $company['name'];
    $_SESSION['financial_year_id'] = (int) $financialYear['id'];
    $_SESSION['financial_year_name'] = $financialYear['name'];
    $_SESSION['financial_year_start'] = $financialYear['start_date'];
    $_SESSION['financial_year_end'] = $financialYear['end_date'];
}
