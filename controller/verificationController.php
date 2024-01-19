<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ErrorLogger;
use Functions\ManageResponse;
use Functions\ManageUuid;
use Functions\Validator;
use viceNet\EmailVerification;

SessionManager::start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $email = SessionManager::get('verificationEmail');
    $mail = new EmailVerification();

    switch ($action) {
        case 'resend':
            handleResendVerification( $mail, $email);
            break;

        case 'verify':
            handleVerifyVerification( $mail, $email);
            break;
        
        default:
            handleError();
            break;
    }
} else {
    ManageResponse::handleInvalidRequest();
}

function handleResendVerification( EmailVerification $mail, $email ) {

    if ($mail->sendVerificationEmail( $email)) {
        SessionManager::setMessage("We've sent the email verification. Please check your inbox and follow the instructions to complete the process and enjoy full access.");
        $response = ['status' => true];
        ManageResponse::sendResponse( $response);
    }

    SessionManager::setMessage("Oops! We couldn't send the email verification. Please verify your email address or try again later. If the problem persists, contact support for assistance.");
    $response = ['status' => false , 'error' => "Oops! We couldn't send the email verification. Please verify your email address or try again later. If the problem persists, contact support for assistance."];
    ManageResponse::sendResponse( $response);
}

function handleVerifyVerification( EmailVerification $mail, $email) {
    $verificationCode = Validator::validateInput( $_POST['verificationCode']);

    $result = $mail->verifyEmail( $email, $verificationCode);

    if($result === 'true') {
        SessionManager::remove('verificationEmail');
        SessionManager::setRedirect('login');
        ManageResponse::sendResponse(['status' => true]);
    }

    handleVerificationResult( $result);
}

function handleVerificationResult( $result) : void {
    switch ($result) {

        case 'maximum attempts try':
            $message = "Oops! You've reached the maximum verification attempts. Please wait for some time before trying again, or contact support for further assistance.";
            break;
        
        case 'wrong':
            $message = "Oops! Incorrect verification code. Please double-check and enter the correct code to proceed.";
            break;

        case 'expire':
            $message = "Sorry! The verification code has expired. Please request a new code and complete the verification within the specified timeframe.";
            break;
        
        case 'no data':
            $message = "Sorry, please try again later. We're working to resolve the issue. Thank you for your patience.";
            break;

        default:
            $message = "Unexpected error. Please try again later. If the issue persists, contact support for assistance.";
            break;
    }

    SessionManager::setMessage($message);
    $response = ['status' => false , 'error' => $message];
    ManageResponse::sendResponse( $response);
}

function handleError() {
    $response = ['status' => false , 'error' => "Unexpected error. Please try again later. If the issue persists, contact support for assistance."];
    SessionManager::setMessage("Unexpected error. Please try again later. If the issue persists, contact support for assistance.");
    ManageResponse::sendResponse( $response);
}