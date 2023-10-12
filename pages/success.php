<?php

require_once __DIR__."/../config/SessionConfig.php";

displayWelcomeMessage();

function displayWelcomeMessage() : void {
    if (!empty($_SESSION['user_name'])) {
        echo "welcome".$_SESSION['user_name'];
    }
    // Unset all session variables
    session_unset();
    
    // Optionally, destroy the session (removing the session cookie as well)
    session_destroy();
}