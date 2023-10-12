<?php
require_once __DIR__."/../config/SessionConfig.php";

function redirectTo($location, $sessionData = null)
{
    if (is_array($sessionData) && !empty($sessionData)) {
        foreach ($sessionData as $sessionName => $message) {
            $_SESSION[$sessionName] = $message;
        }
    }
    header("Location: ../pages/$location");
    exit;
}
