<?php

declare(strict_types=1);

// Include the flash utility functions.
require_once __DIR__ . '/flash.php';

// Retrieve any active notification message stored in the session context.
$flash = getFlash();

?>

<?php if ($flash): ?>

    <!-- Display the styled banner. Alert classes (e.g. 'alert-success') match the theme. -->
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>

<?php endif; ?>