<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';

// Include repository helper queries (getFinancialYears).
require_once __DIR__ . '/repository.php';

$companyId = (int) $_SESSION['company_id'];

// Retrieve a list of all historical and future financial year periods configured for this company.
$financialYears = getFinancialYears(
    $pdo,
    $companyId
);

$pageTitle = 'Financial Years';

// Includes layouts.
require_once __DIR__ . '/../includes/header.php';

?>


<div class="page-header">

    <div>

        <h1>Financial Years</h1>

        <p>
            Manage accounting periods for your company.
        </p>

    </div>


    <a
        href="<?= BASE_URL ?>financial-year/create.php"
        class="btn btn-primary"
    >
        + New Financial Year
    </a>

</div>


<div class="card">

    <div class="card-header">

        <h2>
            Financial Years
        </h2>

        <span>
            <?= count($financialYears) ?> period(s)
        </span>

    </div>


    <?php if (empty($financialYears)): ?>

        <p>
            No financial years have been created.
        </p>

    <?php else: ?>

        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            Financial Year
                        </th>

                        <th>
                            Start Date
                        </th>

                        <th>
                            End Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php foreach ($financialYears as $financialYear): ?>

                    <tr>

                        <td>
                            <strong>
                                <?= htmlspecialchars(
                                    $financialYear['name']
                                ) ?>
                            </strong>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                date(
                                    'd M Y',
                                    strtotime(
                                        $financialYear['start_date']
                                    )
                                )
                            ) ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                date(
                                    'd M Y',
                                    strtotime(
                                        $financialYear['end_date']
                                    )
                                )
                            ) ?>
                        </td>


                        <td>

                            <?php if ((int) $financialYear['is_active'] === 1): ?>

                                <span class="status-badge status-active">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="status-badge status-inactive">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>

                            <div class="table-actions">

                                <a
                                    href="<?= BASE_URL ?>financial-year/edit.php?id=<?= (int) $financialYear['id'] ?>"
                                    class="action-link"
                                >
                                    Edit
                                </a>


                                <?php if ((int) $financialYear['is_active'] !== 1): ?>

                                    <a
                                        href="<?= BASE_URL ?>financial-year/activate.php?id=<?= (int) $financialYear['id'] ?>"
                                        class="action-link action-primary"
                                    >
                                        Activate
                                    </a>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>