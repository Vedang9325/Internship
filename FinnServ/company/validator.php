<?php

declare(strict_types=1);

function validateCompany(array $data): array
{
    $errors = [];

    if (trim($data['name'] ?? '') === '') {
        $errors['name'] = 'Company name is required.';
    }

    if (
        !empty($data['email']) &&
        !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if (
        !empty($data['gstin']) &&
        !preg_match(
            '/^[0-9A-Z]{15}$/',
            strtoupper($data['gstin'])
        )
    ) {
        $errors['gstin'] = 'GSTIN must contain exactly 15 characters.';
    }

    if (
        !empty($data['pincode']) &&
        !preg_match('/^[0-9]{6}$/', $data['pincode'])
    ) {
        $errors['pincode'] = 'Pincode must contain exactly 6 digits.';
    }

    return $errors;
}