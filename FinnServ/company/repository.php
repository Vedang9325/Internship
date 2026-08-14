<?php

declare(strict_types=1);

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


function updateCompany(
    PDO $pdo,
    int $companyId,
    array $data
): bool {

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