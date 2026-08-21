<?php
declare(strict_types=1);

// Set a flash message in session
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Retrieve and clear flash message from session
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    
    if ($flash !== null) {
        unset($_SESSION['flash']);
    }
    
    return $flash;  
}