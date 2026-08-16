<?php

declare(strict_types=1);

// Bootstrapping and session protection check.
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

// Include utilities (Flash alerts, SQL queries, input validation).
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/validator.php';

// Guard: Route must only accept POST submissions. Block direct url GET requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'company/');
    exit;
}

// Retrieve active company ID context.
$companyId = (int) ($_SESSION['company_id'] ?? 1);

// Standardize and sanitize inputs. Convert GSTIN to uppercase.
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

// PRG Pattern: If validation fails, store errors & old input data in session and redirect back.
if (!empty($errors)) {
    $_SESSION['company_errors'] = $errors;
    $_SESSION['company_old'] = $data;

    header('Location: ' . BASE_URL . 'company/edit.php');
    exit;
}

try {

    // Trigger update query execution in database.
    $updated = updateCompany(
        $pdo,
        $companyId,
        $data
    );

    if (!$updated) {
        throw new RuntimeException('Company could not be updated.');
    }

    // Set success alert notification.
    setFlash('success', 'Company details updated successfully.');

    // Redirect to profile display view.
    header('Location: ' . BASE_URL . 'company/');
    exit;

} catch (Throwable $e) {

    // Set failure notification banner.
    setFlash('error', 'Unable to update company details.');

    // Redirect to edit page for correction.
    header('Location: ' . BASE_URL . 'company/edit.php');
    exit;
}