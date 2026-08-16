<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/validator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'company/');
    exit;
}

$companyId = (int) ($_SESSION['company_id'] ?? 1);

$data = [
    'name'         => trim($_POST['name'] ?? ''),
    'mailing_name' => trim($_POST['mailing_name'] ?? ''),
    'address'      => trim($_POST['address'] ?? ''),
    'state'        => trim($_POST['state'] ?? ''),
    'country'      => trim($_POST['country'] ?? ''),
    'pincode'      => trim($_POST['pincode'] ?? ''),
    'phone'        => trim($_POST['phone'] ?? ''),
    'email'        => trim($_POST['email'] ?? ''),
    'gstin'        => strtoupper(trim($_POST['gstin'] ?? '')),
];

// Perform schema validation on inputs.
$errors = validateCompany($data);

if (!empty($errors)) {
    $_SESSION['company_errors'] = $errors;
    $_SESSION['company_old'] = $data;

    header('Location: ' . BASE_URL . 'company/edit.php');
    exit;
}

try {

    $updated = updateCompany(
        $pdo,
        $companyId,
        $data
    );

    if (!$updated) {
        throw new RuntimeException('Company could not be updated.');
    }

    setFlash('success', 'Company details updated successfully.');

    header('Location: ' . BASE_URL . 'company/');
    exit;

} catch (Throwable $e) {

    setFlash('error', 'Unable to update company details.');

    header('Location: ' . BASE_URL . 'company/edit.php');
    exit;
}
