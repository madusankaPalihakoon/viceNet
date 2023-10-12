<?php
require_once __DIR__."/../config/SessionConfig.php";

function displayLoginErrorMessage() : void {
    if (isset($_SESSION['login_error'])) 
    {
        echo $_SESSION['login_error'];
    }
    unset($_SESSION['login_error']);
}