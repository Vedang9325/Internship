<?php
declare(strict_types=1);

// Get specific company record by ID
function findCompany(PDO $pdo, int $companyId): ?array
{
    $stmt = $pdo->prepare("SELECT id, name, mailing_name, address, state, country, pincode, phone, email, gstin FROM companies WHERE id = ? LIMIT 1");
    $stmt->execute([$companyId]);
    return $stmt->fetch() ?: null;
}

// Update company profile details
function updateCompany(PDO $pdo, int $companyId, array $data): bool
{
    $sql = "UPDATE companies SET name = :name, mailing_name = :mailing_name, address = :address, state = :state, country = :country, pincode = :pincode, phone = :phone, email = :email, gstin = :gstin WHERE id = :id";
    $data['id'] = $companyId;
    return $pdo->prepare($sql)->execute($data);
}

// Fetch all registered companies ordered by name
function getAllCompanies(PDO $pdo): array
{
    return $pdo->query("SELECT id, name, state, country FROM companies ORDER BY name ASC")->fetchAll();
}