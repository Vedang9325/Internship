<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

echo '<pre>';

echo "company_id: ";
var_dump($_SESSION['company_id'] ?? 'NOT SET');

echo "\ncompany_name: ";
var_dump($_SESSION['company_name'] ?? 'NOT SET');

echo "\nfinancial_year_id: ";
var_dump($_SESSION['financial_year_id'] ?? 'NOT SET');

echo "\nfinancial_year_name: ";
var_dump($_SESSION['financial_year_name'] ?? 'NOT SET');

echo "\n\nFULL SESSION:\n";
print_r($_SESSION);

echo '</pre>';