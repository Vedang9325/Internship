<?php

declare(strict_types=1);

/**
 * Stores a notification message in session variables.
 * It will display on the next page loading cycle.
 *
 * @param string $type The alert styling category ('success' or 'error').
 * @param string $message The notification message text.
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Fetches and deletes the alert message from session memory.
 * This guarantees the alert banner only renders once to the client.
 *
 * @return array|null The message payload or null if no flash is set.
 */
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;

    // Clear flash data from session so it doesn't display again on refresh.
    if ($flash !== null) {
        unset($_SESSION['flash']);
    }

    return $flash;
}