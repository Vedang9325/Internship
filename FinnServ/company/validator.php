<?php

declare(strict_types=1);

/**
 * Validates company data rules for business compliance.
 *
 * @param array $data Input payload associative array.
 * @return array Validation errors where keys match input names.
 */
function validateCompany(array $data): array
{
    $errors = [];

    // Rule 1: Company name is a required field.
    if (trim($data['name'] ?? '') === '') {
        $errors['name'] = 'Company name is required.';
    }

    // Rule 2: If email is provided, verify it has a valid email domain format.
    if (
        !empty($data['email']) &&
        !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $errors['email'] = 'Enter a valid email address.';
    }

    // Rule 3: If GSTIN is provided, verify it has exactly 15 alphanumeric characters.
    if (
        !empty($data['gstin']) &&
        !preg_match(
            '/^[0-9A-Z]{15}$/',
            strtoupper($data['gstin'])
        )
    ) {
        $errors['gstin'] = 'GSTIN must contain exactly 15 characters.';
    }

    // Rule 4: If pincode is provided, verify it is a valid 6-digit number (Indian Postal Code standard).
    if (
        !empty($data['pincode']) &&
        !preg_match('/^[0-9]{6}$/', $data['pincode'])
    ) {
        $errors['pincode'] = 'Pincode must contain exactly 6 digits.';
    }

    return $errors;
}