<?php

// Include initial bootstrap scripts (configurations, DB context, and session check).
require_once __DIR__ . '/includes/init.php';

// Diagnostics Check: Verify database is fully functional by fetching
// company #1 along with its current active financial year period.
$stmt = $pdo->query("
    SELECT
        c.name AS company_name,
        fy.name AS financial_year
    FROM companies c
    INNER JOIN financial_years fy
        ON fy.company_id = c.id
    WHERE c.id = 1
      AND fy.is_active = 1
    LIMIT 1
");

// Fetch the result row as an associative array.
$company = $stmt->fetch();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= APP_NAME ?></title>
</head>

<body>

    <h1><?= APP_NAME ?></h1>

    <?php if ($company): ?>

        <p>Database connection: <strong>Successful</strong></p>

        <p>
            Company:
            <strong><?= htmlspecialchars($company['company_name']) ?></strong>
        </p>

        <p>
            Financial Year:
            <strong><?= htmlspecialchars($company['financial_year']) ?></strong>
        </p>

    <?php else: ?>

        <p>Company information could not be loaded.</p>

    <?php endif; ?>

</body>

</html>