<?php
declare(strict_types=1);

// Get list of all financial years of a company
function getFinancialYears(PDO $pdo, int $companyId): array
{
    $stmt = $pdo->prepare("SELECT id, company_id, name, start_date, end_date, is_active, created_at FROM financial_years WHERE company_id = ? ORDER BY start_date DESC");
    $stmt->execute([$companyId]);
    return $stmt->fetchAll();
}

// Get details of a specific financial year
function getFinancialYear(PDO $pdo, int $financialYearId, int $companyId): ?array
{
    $stmt = $pdo->prepare("SELECT id, company_id, name, start_date, end_date, is_active FROM financial_years WHERE id = ? AND company_id = ? LIMIT 1");
    $stmt->execute([$financialYearId, $companyId]);
    return $stmt->fetch() ?: null;
}

// Check if financial year name exists for a company
function financialYearExists(PDO $pdo, int $companyId, string $name, ?int $excludeId = null): bool
{
    $sql = "SELECT id FROM financial_years WHERE company_id = ? AND name = ?";
    $params = [$companyId, $name];
    if ($excludeId !== null) {
        $sql .= " AND id != ?";
        $params[] = $excludeId;
    }
    $stmt = $pdo->prepare($sql . " LIMIT 1");
    $stmt->execute($params);
    return (bool)$stmt->fetchColumn();
}

// Create a new inactive financial year
function createFinancialYear(PDO $pdo, int $companyId, array $data): int
{
    $stmt = $pdo->prepare("INSERT INTO financial_years (company_id, name, start_date, end_date, is_active) VALUES (?, ?, ?, ?, 0)");
    $stmt->execute([$companyId, $data['name'], $data['start_date'], $data['end_date']]);
    return (int)$pdo->lastInsertId();
}

// Update specific financial year
function updateFinancialYear(PDO $pdo, int $financialYearId, int $companyId, array $data): bool
{
    $stmt = $pdo->prepare("UPDATE financial_years SET name = ?, start_date = ?, end_date = ? WHERE id = ? AND company_id = ?");
    return $stmt->execute([$data['name'], $data['start_date'], $data['end_date'], $financialYearId, $companyId]);
}

// Set active financial year for a company
function activateFinancialYear(PDO $pdo, int $financialYearId, int $companyId): bool
{
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("SELECT id FROM financial_years WHERE id = ? AND company_id = ? LIMIT 1");
        $stmt->execute([$financialYearId, $companyId]);
        if (!$stmt->fetchColumn()) {
            throw new RuntimeException('Financial year not found.');
        }

        // Deactivate all company's financial years
        $pdo->prepare("UPDATE financial_years SET is_active = 0 WHERE company_id = ?")->execute([$companyId]);
        
        // Activate target year
        $pdo->prepare("UPDATE financial_years SET is_active = 1 WHERE id = ? AND company_id = ?")->execute([$financialYearId, $companyId]);

        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
