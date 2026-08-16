<?php

declare(strict_types=1);

require_once __DIR__ . '/flash.php';

$flash = getFlash();

?>

<?php if ($flash): ?>

    <!-- Display the styled banner. Alert classes (e.g. 'alert-success') match the theme. -->
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>

<?php endif; ?>