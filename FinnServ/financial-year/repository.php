<?php

declare(strict_types=1);


/**
 * Fetches all financial accounting periods registered under a specific company.
 * Order is sorted descending by date so that newer periods display first.
 *
 * @param PDO $pdo Active database connection.
 * @param int $companyId The current company context.
 * @return array List of accounting periods.
 */
function getFinancialYears(
    PDO $pdo,
    int $companyId
): array {

    $stmt = $pdo->prepare("
        SELECT
            id,
            company_id,
            name,
            start_date,
            end_date,
            is_active,
            created_at
        FROM financial_years
        WHERE company_id = ?
        ORDER BY start_date DESC
    ");

    $stmt->execute([$companyId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Fetches details of a specific financial year by its ID and company context.
 *
 * @param PDO $pdo Database resource handler.
 * @param int $financialYearId Target period ID.
 * @param int $companyId Target company context to avoid cross-tenant leaks.
 * @return array|null The period row or null if not found.
 */
function getFinancialYear(
    PDO $pdo,
    int $financialYearId,
    int $companyId
): ?array {

    $stmt = $pdo->prepare("
        SELECT
            id,
            company_id,
            name,
            start_date,
            end_date,
            is_active
        FROM financial_years
        WHERE id = ?
          AND company_id = ?
        LIMIT 1
    ");

    $stmt->execute([
        $financialYearId,
        $companyId
    ]);

    $financialYear = $stmt->fetch(PDO::FETCH_ASSOC);

    return $financialYear ?: null;
}


/**
 * Verifies if a financial year name is already registered for a company.
 * Useful to prevent duplicate definitions (e.g., creating two '2027-28' rows).
 *
 * @param PDO $pdo Database resource handler.
 * @param int $companyId Target company.
 * @param string $name The financial year identifier (e.g. '2027-28').
 * @param int|null $excludeId An optional ID to ignore (used during alteration edits).
 * @return bool True if duplicate exists, false otherwise.
 */
function financialYearExists(
    PDO $pdo,
    int $companyId,
    string $name,
    ?int $excludeId = null
): bool {

    $sql = "
        SELECT id
        FROM financial_years
        WHERE company_id = ?
          AND name = ?
    ";

    $params = [
        $companyId,
        $name
    ];

    // If updating an existing year, ignore the record being edited itself during query.
    if ($excludeId !== null) {
        $sql .= " AND id != ?";
        $params[] = $excludeId;
    }

    $sql .= " LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    return (bool) $stmt->fetchColumn();
}


/**
 * Registers a new financial accounting period.
 * Starts as inactive (is_active = 0) by default. The user must manually activate it.
 *
 * @param PDO $pdo Database handler.
 * @param int $companyId Associated company.
 * @param array $data Input payload dates.
 * @return int Inserted primary key ID.
 */
function createFinancialYear(
    PDO $pdo,
    int $companyId,
    array $data
): int {

    $stmt = $pdo->prepare("
        INSERT INTO financial_years (
            company_id,
            name,
            start_date,
            end_date,
            is_active
        )
        VALUES (?, ?, ?, ?, 0)
    ");

    $stmt->execute([
        $companyId,
        $data['name'],
        $data['start_date'],
        $data['end_date']
    ]);

    return (int) $pdo->lastInsertId();
}


/**
 * Updates date ranges or name tags for a financial year period.
 *
 * @param PDO $pdo Database handler.
 * @param int $financialYearId Target period.
 * @param int $companyId Associated company.
 * @param array $data New parameters.
 * @return bool True on success, false on failure.
 */
function updateFinancialYear(
    PDO $pdo,
    int $financialYearId,
    int $companyId,
    array $data
): bool {

    $stmt = $pdo->prepare("
        UPDATE financial_years
        SET
            name = ?,
            start_date = ?,
            end_date = ?
        WHERE id = ?
          AND company_id = ?
    ");

    return $stmt->execute([
        $data['name'],
        $data['start_date'],
        $data['end_date'],
        $financialYearId,
        $companyId
    ]);
}


/**
 * Shifts active periods (Alt+F2 context switch).
 * Deactivates all periods for this company, then activates the chosen one.
 * Uses SQL transactions to guarantee consistency (either both updates succeed, or both roll back).
 *
 * @param PDO $pdo Database handler.
 * @param int $financialYearId Period to activate.
 * @param int $companyId Associated company context.
 * @return bool True on success.
 * @throws RuntimeException If target financial year does not exist.
 */
function activateFinancialYear(
    PDO $pdo,
    int $financialYearId,
    int $companyId
): bool {

    try {

        // Begin transaction block to lock records during update queries.
        $pdo->beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | Verify Financial Year Existence
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT id
            FROM financial_years
            WHERE id = ?
              AND company_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $financialYearId,
            $companyId
        ]);

        if (!$stmt->fetchColumn()) {

            throw new RuntimeException(
                'Financial year not found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Deactivate Current Financial Year
        |--------------------------------------------------------------------------
        |
        | Ensure no other periods are flagged active.
        |
        */

        $stmt = $pdo->prepare("
            UPDATE financial_years
            SET is_active = 0
            WHERE company_id = ?
        ");

        $stmt->execute([
            $companyId
        ]);


        /*
        |--------------------------------------------------------------------------
        | Activate Selected Financial Year
        |--------------------------------------------------------------------------
        |
        | Turn on active flag context for target ID.
        |
        */

        $stmt = $pdo->prepare("
            UPDATE financial_years
            SET is_active = 1
            WHERE id = ?
              AND company_id = ?
        ");

        $stmt->execute([
            $financialYearId,
            $companyId
        ]);


        // Commit transaction to finalize changes on database file tables.
        $pdo->commit();

        return true;

    } catch (Throwable $e) {

        // Roll back updates if any query in try block fails to avoid corrupt data states.
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}