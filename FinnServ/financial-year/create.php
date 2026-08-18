<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

$errors = $_SESSION['financial_year_errors'] ?? [];
$old = $_SESSION['financial_year_old'] ?? [];
unset($_SESSION['financial_year_errors'], $_SESSION['financial_year_old']);

$pageTitle = 'New Financial Year';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1>New Financial Year</h1>
        <p>Create a new accounting period for your company.</p>
    </div>
    <a href="<?= BASE_URL ?>financial-year/" class="btn">Cancel</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>financial-year/save.php" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Financial Year *</label>
                <input type="text" id="name" name="name" placeholder="e.g. 2027-28" maxlength="20" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['name']) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                <label for="start_date">Start Date *</label>
                <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($old['start_date'] ?? '') ?>" required>
                <?php if (isset($errors['start_date'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['start_date']) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="end_date">End Date *</label>
                <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($old['end_date'] ?? '') ?>" required>
                <?php if (isset($errors['end_date'])): ?>
                    <small class="field-error"><?= htmlspecialchars($errors['end_date']) ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-actions">
            <a href="<?= BASE_URL ?>financial-year/" class="btn">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Financial Year</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>