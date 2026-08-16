<?php

declare(strict_types=1);

/**
 * Retrieves details for a specific company by its ID.
 *
 * @param PDO $pdo Active database connection wrapper.
 * @param int $companyId The target company ID.
 * @return array|null The company row as an associative array or null if not found.
 */
function getCompany(PDO $pdo, int $companyId): ?array
{
    $stmt = $pdo->prepare("
        SELECT
            id,
            name,
            mailing_name,
            address,
            state,
            country,
            pincode,
            phone,
            email,
            gstin
        FROM companies
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$companyId]);

    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    return $company ?: null;
}


/**
 * Updates metadata parameters for an existing company record.
 * Similar to updating the F3: Company Alteration details screen in Tally.
 *
 * @param PDO $pdo Active database connection.
 * @param int $companyId ID of the company to update.
 * @param array $data Sanitized input payload from the edit form.
 * @return bool True on query success, false otherwise.
 */
function updateCompany(
    PDO $pdo,
    int $companyId,
    array $data
): bool {

    // Parameterized UPDATE statement to protect columns from injection.
    $stmt = $pdo->prepare("
        UPDATE companies
        SET
            name = ?,
            mailing_name = ?,
            address = ?,
            state = ?,
            country = ?,
            pincode = ?,
            phone = ?,
            email = ?,
            gstin = ?
        WHERE id = ?
    ");

    // Execute query with array mapping values to the placeholder ? markers sequentially.
    return $stmt->execute([
        $data['name'],
        $data['mailing_name'],
        $data['address'],
        $data['state'],
        $data['country'],
        $data['pincode'],
        $data['phone'],
        $data['email'],
        $data['gstin'],
        $companyId
    ]);
}