<?php

declare(strict_types=1);


/**
 * Validates financial year boundaries.
 * Ensures start date comes before the end date.
 *
 * @param array $data Input dates.
 * @return array Validation errors.
 */
function validateFinancialYear(array $data): array
{
    $errors = [];

    $name = trim($data['name'] ?? '');
    $startDate = trim($data['start_date'] ?? '');
    $endDate = trim($data['end_date'] ?? '');


    /*
    |--------------------------------------------------------------------------
    | Name Verification
    |--------------------------------------------------------------------------
    */

    if ($name === '') {

        $errors['name'] =
            'Financial year name is required.';

    } elseif (strlen($name) > 20) {

        $errors['name'] =
            'Financial year name cannot exceed 20 characters.';
    }


    /*
    |--------------------------------------------------------------------------
    | Start Date Verification
    |--------------------------------------------------------------------------
    */

    if ($startDate === '') {

        $errors['start_date'] =
            'Start date is required.';
    }


    /*
    |--------------------------------------------------------------------------
    | End Date Verification
    |--------------------------------------------------------------------------
    */

    if ($endDate === '') {

        $errors['end_date'] =
            'End date is required.';
    }


    /*
    |--------------------------------------------------------------------------
    | Date Logic Boundary Check
    |--------------------------------------------------------------------------
    |
    | Verifies that the start date precedes the end date.
    | Handles formatting exceptions cleanly using PHP DateTimeImmutable classes.
    |
    */

    if (
        $startDate !== '' &&
        $endDate !== ''
    ) {

        try {

            // Parse text date inputs into immutable date-time instances.
            $start = new DateTimeImmutable($startDate);
            $end = new DateTimeImmutable($endDate);

            // Compare date scales.
            if ($start >= $end) {

                $errors['end_date'] =
                    'End date must be after start date.';
            }

        } catch (Exception $e) {

            // Catch corrupt date format submissions (like 2027-02-31).
            $errors['start_date'] =
                'Invalid date.';
        }
    }


    return $errors;
}