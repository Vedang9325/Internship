<?php

declare(strict_types=1);

// Bootstrapping and session protection check.
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

// Include DB access functions (getCompany).
require_once __DIR__ . '/repository.php';

// Get target company identifier context.
$companyId = (int) ($_SESSION['company_id'] ?? 1);

// Pull existing settings info from database to pre-fill form fields.
$company = getCompany($pdo, $companyId);

if (!$company) {
    die('Company not found.');
}

$pageTitle = 'Edit Company';

require_once __DIR__ . '/../includes/header.php';
?>


<div class="page-header">
    <div>
        <h1>Edit Company</h1>
        <p>Update your company information.</p>
    </div>

    <a
        href="<?= BASE_URL ?>company/"
        class="btn"
    >
        Cancel
    </a>
</div>

<div class="card">

    <form
        action="<?= BASE_URL ?>company/save.php"
        method="POST"
    >

        <div class="form-grid">

            <div class="form-group">
                <label for="name">
                    Company Name *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($company['name']) ?>"
                    required
                >
            </div>


            <div class="form-group">
                <label for="mailing_name">
                    Mailing Name
                </label>

                <input
                    type="text"
                    id="mailing_name"
                    name="mailing_name"
                    value="<?= htmlspecialchars($company['mailing_name'] ?? '') ?>"
                >
            </div>


            <div class="form-group form-full">
                <label for="address">
                    Address
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="3"
                ><?= htmlspecialchars($company['address'] ?? '') ?></textarea>
            </div>


            <div class="form-group">
                <label for="state">
                    State
                </label>

                <input
                    type="text"
                    id="state"
                    name="state"
                    value="<?= htmlspecialchars($company['state'] ?? '') ?>"
                >
            </div>


            <div class="form-group">
                <label for="country">
                    Country
                </label>

                <input
                    type="text"
                    id="country"
                    name="country"
                    value="<?= htmlspecialchars($company['country'] ?? '') ?>"
                >
            </div>


            <div class="form-group">
                <label for="pincode">
                    Pincode
                </label>

                <input
                    type="text"
                    id="pincode"
                    name="pincode"
                    maxlength="6"
                    value="<?= htmlspecialchars($company['pincode'] ?? '') ?>"
                >
            </div>


            <div class="form-group">
                <label for="phone">
                    Phone
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($company['phone'] ?? '') ?>"
                >
            </div>


            <div class="form-group">
                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($company['email'] ?? '') ?>"
                >
            </div>


            <div class="form-group">
                <label for="gstin">
                    GSTIN
                </label>

                <input
                    type="text"
                    id="gstin"
                    name="gstin"
                    maxlength="15"
                    value="<?= htmlspecialchars($company['gstin'] ?? '') ?>"
                >
            </div>

        </div>


        <div class="form-actions">

            <a
                href="<?= BASE_URL ?>company/"
                class="btn"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>