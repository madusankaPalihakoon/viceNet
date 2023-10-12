<?php
require_once __DIR__."/../config/SessionConfig.php";

function displayVerificationMessage() : void {
    if (isset($_SESSION['verification_messages']) || isset($_SESSION['verification_attempt']))
    {
        if (isset($_SESSION['verification_messages'])) {
            echo $_SESSION['verification_messages'];
            echo '<br>';
        }
        if (isset($_SESSION['verification_attempt'])) {
            echo $_SESSION['verification_attempt'];
            echo '<br>';
        }
        unset($_SESSION['verification_messages']);
    }
}

function displayResendVerifyMessage() : void {
    if (isset($_SESSION['resend_messages'])) 
    {
        echo $_SESSION['resend_messages'];
    }
    unset($_SESSION['resend_messages']);
}