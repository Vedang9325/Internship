<?php

declare(strict_types=1);

// Initialize app config database connection and check if user session is active.
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

// Configure page parameters. Will render in title tags and top banner.
$pageTitle = 'Dashboard';

// Include central layout framework. Loads active company/FY details from database.
require_once __DIR__ . '/../includes/header.php';

?>


<section class="dashboard-welcome">

    <h1>
        Welcome back,
        <?= htmlspecialchars($_SESSION['user_name']) ?>
    </h1>

    <p>
        Here's an overview of your FinnServ accounting environment.
    </p>

</section>


<!-- Statistics -->

<section class="stats-grid">

    <div class="card">

        <div class="stat-label">
            COMPANY
        </div>

        <div class="stat-value">
            #<?= (int) $_SESSION['company_id'] ?>
        </div>

        <div class="stat-description">
            Active company
        </div>

    </div>


    <div class="card">

        <div class="stat-label">
            FINANCIAL YEAR
        </div>

        <div class="stat-value">
            <?= htmlspecialchars(
                $_SESSION['financial_year_name'] ?? 'N/A'
            ) ?>
        </div>

        <div class="stat-description">
            Current accounting period
        </div>

    </div>


    <div class="card">

        <div class="stat-label">
            USER ROLE
        </div>

        <div class="stat-value">
            <?= htmlspecialchars($_SESSION['role']) ?>
        </div>

        <div class="stat-description">
            Current access level
        </div>

    </div>


    <div class="card">

        <div class="stat-label">
            SYSTEM
        </div>

        <div class="stat-value">
            v<?= htmlspecialchars(APP_VERSION) ?>
        </div>

        <div class="stat-description">
            FinnServ application
        </div>

    </div>

</section>


<!-- Dashboard Panels -->

<section class="dashboard-grid">


    <!-- Quick Actions -->

    <div class="card">

        <div class="card-header">

            <h2>
                Quick Actions
            </h2>

            <span>
                Frequently used
            </span>

        </div>


        <div class="quick-actions">

            <a href="#" class="quick-action">

                <strong>
                    Create Ledger
                </strong>

                <span>
                    Add a new account ledger
                </span>

            </a>


            <a href="#" class="quick-action">

                <strong>
                    Payment
                </strong>

                <span>
                    Record a payment voucher
                </span>

            </a>


            <a href="#" class="quick-action">

                <strong>
                    Receipt
                </strong>

                <span>
                    Record a receipt voucher
                </span>

            </a>


            <a href="#" class="quick-action">

                <strong>
                    Sales Invoice
                </strong>

                <span>
                    Create a sales transaction
                </span>

            </a>

        </div>

    </div>


    <!-- System Information -->

    <div class="card">

        <div class="card-header">

            <h2>
                Current Session
            </h2>

        </div>


        <p class="stat-description">
            Logged in as
        </p>

        <p style="margin-bottom: 15px; font-weight: 600;">
            <?= htmlspecialchars($_SESSION['user_name']) ?>
        </p>


        <p class="stat-description">
            Financial Year
        </p>

        <p style="margin-bottom: 15px; font-weight: 600;">
            <?= htmlspecialchars(
                $_SESSION['financial_year_name'] ?? 'N/A'
            ) ?>
        </p>


        <p class="stat-description">
            Access Role
        </p>

        <p style="font-weight: 600;">
            <?= htmlspecialchars($_SESSION['role']) ?>
        </p>

    </div>

</section>

<?php

require_once __DIR__ . '/../includes/footer.php';

?>