<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/validator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'financial-year/');
    exit;
}

// Ensure active company context is selected
if (!isset($_SESSION['company_id']) || (int)$_SESSION['company_id'] <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$companyId = (int)$_SESSION['company_id'];
$data = [
    'name' => trim($_POST['name'] ?? ''),
    'start_date' => trim($_POST['start_date'] ?? ''),
    'end_date' => trim($_POST['end_date'] ?? ''),
];

$errors = validateFinancialYear($data);
if (!empty($errors)) {
    $_SESSION['financial_year_errors'] = $errors;
    $_SESSION['financial_year_old'] = $data;
    header('Location: ' . BASE_URL . 'financial-year/create.php');
    exit;
}

if (financialYearExists($pdo, $companyId, $data['name'])) {
    setFlash('error', 'A financial year with this name already exists.');
    $_SESSION['financial_year_old'] = $data;
    header('Location: ' . BASE_URL . 'financial-year/create.php');
    exit;
}

try {
    createFinancialYear($pdo, $companyId, $data);
    setFlash('success', 'Financial year created successfully.');
    header('Location: ' . BASE_URL . 'financial-year/');
    exit;
} catch (Throwable $e) {
    setFlash('error', 'Unable to create financial year.');
    header('Location: ' . BASE_URL . 'financial-year/create.php');
    exit;
}
