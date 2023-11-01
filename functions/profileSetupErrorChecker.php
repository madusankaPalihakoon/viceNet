<?php
require_once __DIR__."/../config/SessionConfig.php";

function displayProfileSetupErrorMessage() : void {
    if (isset($_SESSION['profile_setup_error'])) 
    {
        echo $_SESSION['profile_setup_error'];
    }
    unset($_SESSION['profile_setup_error']);
}