<?php
require_once __DIR__ . "/../config/SessionConfig.php";
require_once __DIR__ . "/../functions/InputSanitizer.php";
require_once __DIR__ . "/../functions/redirectFunction.php";
require_once __DIR__ . "/../functions/attemptCounter.php";
require_once __DIR__ . "/../classes/Classes/User.php";
require_once __DIR__ . "/../autoload.php";
use ViceNet\Classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formType = $_POST['form_type'] ?? '';

    switch ($formType) {
        case 'verify_code':
            handleVerificationCode();
            break;

        case 'resend_code':
            handleResendCode();
            break;

        default:
            redirectToVerifyEmail();
            break;
    }
}
unset($_SESSION['verification_attempt'],$_SESSION['verification_email']);

function handleVerificationCode()
{
    $user = new User();
    $email = $_POST['verification_email'] ?? '';
    $code = $_POST['verification_code'] ?? '';

    if (!checkAttemptCountIsOver()) {
        $email = $_POST['verification_email'] ?? '';
        $code = $_POST['verification_code'] ?? '';

        if ($user->isVerificationCodeValid($email, $code)) {
            $user->updateVerificationState( $email, '1'); //1 mean verify success

            // get name from database
            $name = $user->getUserName( $email);
            redirectTo('success', ['user_name' => $name]);
        } else {
            redirectTo('verify-email', ['verification_messages' => 'Sorry, the verification code you entered is incorrect or has expired. Please double-check the code and try again.']);
        }
    } else {
        $user->updateVerificationState( $email, '2'); //2 mean email is blocked
        redirectTo('error', ['email_block_error' => 'You have exceeded the maximum allowed attempts. Your email is blocked.']);
        unset($_SESSION['verification_attempt']);
    }
}

function handleResendCode()
{
    $email = $_POST['resend_email'] ?? '';
    $user = new User();

    if ($user->resendVerificationCodeToUser($email)) {
        redirectTo('verify-email', ['resend_messages' => 'Verification code resent successfully. Please check your email.']);
    } else {
        redirectTo('verify-email', ['resend_messages' => 'Failed to resend verification code. Please try again.']);
    }
}

function redirectToVerifyEmail()
{
    header("location: ../pages/verify-email");
    exit;
}
