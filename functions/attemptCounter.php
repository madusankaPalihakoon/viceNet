<?php
require_once __DIR__."/redirectFunction.php";
require_once __DIR__."/../config/SessionConfig.php";

function checkAttemptCountIsOver() {
    // Check if the attempt count is not set, initialize it to 0
    if (!isset($_SESSION['verification_attempt'])) {
        $_SESSION['verification_attempt'] = 0;
    }
    // Check if the attempt count is less than or equal to 2
    if ($_SESSION['verification_attempt'] <= 2) {
        // Increment the attempt count
        $_SESSION['verification_attempt']++;

        return false;
    } else {
        return true;
    }
}

function countLoginAttempt() {
    // Check if the attempt count is not set, initialize it to 0
    if (!isset($_SESSION['login_attempt'])) {
        $_SESSION['login_attempt'] = 0;
    }
    // Check if the attempt count is less than or equal to 2
    if ($_SESSION['login_attempt'] <= 2) {
        // Increment the attempt count
        $_SESSION['login_attempt']++;

        return false;
    } else {
        return true;
    }
}