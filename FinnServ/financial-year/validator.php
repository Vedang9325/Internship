<?php
declare(strict_types=1);

function validateFinancialYear(array $data): array
{
    $errors = [];
    $name = trim($data['name'] ?? '');
    $startDate = trim($data['start_date'] ?? '');
    $endDate = trim($data['end_date'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Financial year name is required.';
    } elseif (strlen($name) > 20) {
        $errors['name'] = 'Financial year name cannot exceed 20 characters.';
    }

    if ($startDate === '') {
        $errors['start_date'] = 'Start date is required.';
    }

    if ($endDate === '') {
        $errors['end_date'] = 'End date is required.';
    }

    if ($startDate !== '' && $endDate !== '') {
        try {
            $start = new DateTimeImmutable($startDate);
            $end = new DateTimeImmutable($endDate);
            if ($start >= $end) {
                $errors['end_date'] = 'End date must be after start date.';
            }
        } catch (Exception $e) {
            $errors['start_date'] = 'Invalid date.';
        }
    }

    return $errors;
}
