<?php

declare(strict_types=1);


/*
|--------------------------------------------------------------------------
| Find Company
|--------------------------------------------------------------------------
*/

function findCompany(
    PDO $pdo,
    int $companyId
): ?array {

    $sql = "
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
        WHERE id = :id
        LIMIT 1
    ";

    $statement = $pdo->prepare($sql);

    $statement->execute([
        ':id' => $companyId,
    ]);

    $company = $statement->fetch(PDO::FETCH_ASSOC);

    return $company ?: null;
}


/*
|--------------------------------------------------------------------------
| Update Company
|--------------------------------------------------------------------------
*/

function updateCompany(
    PDO $pdo,
    int $companyId,
    array $data
): bool {

    $sql = "
        UPDATE companies
        SET
            name = :name,
            mailing_name = :mailing_name,
            address = :address,
            state = :state,
            country = :country,
            pincode = :pincode,
            phone = :phone,
            email = :email,
            gstin = :gstin
        WHERE id = :id
    ";

    $statement = $pdo->prepare($sql);

    return $statement->execute([
        ':name' => $data['name'],
        ':mailing_name' => $data['mailing_name'],
        ':address' => $data['address'],
        ':state' => $data['state'],
        ':country' => $data['country'],
        ':pincode' => $data['pincode'],
        ':phone' => $data['phone'],
        ':email' => $data['email'],
        ':gstin' => $data['gstin'],
        ':id' => $companyId,
    ]);
}


/*
|--------------------------------------------------------------------------
| Get All Companies
|--------------------------------------------------------------------------
*/

function getAllCompanies(PDO $pdo): array
{
    $sql = "
        SELECT
            id,
            name,
            state,
            country
        FROM companies
        ORDER BY name ASC
    ";

    $statement = $pdo->query($sql);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}