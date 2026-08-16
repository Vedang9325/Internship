<?php

declare(strict_types=1);

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;

    // Clear flash data from session so it doesn't display again on refresh.
    if ($flash !== null) {
        unset($_SESSION['flash']);
    }

    return $flash;
}