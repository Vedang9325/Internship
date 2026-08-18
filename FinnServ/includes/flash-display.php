<?php
declare(strict_types=1);

require_once __DIR__ . '/flash.php';

// Retrieve active flash alert message
$flash = getFlash();

if ($flash): ?>
    <!-- Alert display block -->
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>