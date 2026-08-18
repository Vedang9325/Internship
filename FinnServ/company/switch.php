<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/repository.php';

$companyId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$companyId || $companyId <= 0) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$company = findCompany($pdo, (int)$companyId);
if (!$company) {
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, start_date, end_date FROM financial_years WHERE company_id = ? AND is_active = 1 ORDER BY start_date DESC LIMIT 1");
$stmt->execute([$companyId]);
$financialYear = $stmt->fetch();

if (!$financialYear) {
    $_SESSION['company_switch_error'] = 'The selected company does not have an active financial year.';
    header('Location: ' . BASE_URL . 'company/select.php');
    exit;
}

$_SESSION['company_id'] = (int)$company['id'];
$_SESSION['company_name'] = $company['name'];
$_SESSION['financial_year_id'] = (int)$financialYear['id'];
$_SESSION['financial_year_name'] = $financialYear['name'];
$_SESSION['financial_year_start'] = $financialYear['start_date'];
$_SESSION['financial_year_end'] = $financialYear['end_date'];

session_write_close();
header('Location: ' . BASE_URL . 'dashboard/');
exit;