<?php
require_once __DIR__."/../config/SessionConfig.php";

// Get the signup error from the session
function displaySignupError() : void {
    if (!empty($_SESSION['signup_error'])) {
        foreach ($_SESSION['signup_error'] as $error) {
            echo $error;
        }
        unset ($_SESSION['signup_error']);
    }
}
