<?php

declare(strict_types=1);


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

    if ($excludeId !== null) {
        $sql .= " AND id != ?";
        $params[] = $excludeId;
    }

    $sql .= " LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    return (bool) $stmt->fetchColumn();
}

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

function activateFinancialYear(
    PDO $pdo,
    int $financialYearId,
    int $companyId
): bool {

    try {

        $pdo->beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | Verify Financial Year
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


        $pdo->commit();

        return true;

    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}