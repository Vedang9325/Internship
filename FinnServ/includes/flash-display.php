<?php

declare(strict_types=1);

$flash = getFlash();

if ($flash):
?>

    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>

<?php endif; ?>