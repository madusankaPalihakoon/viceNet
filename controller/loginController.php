<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ErrorLogger;
use Functions\ManageResponse;
use Functions\ManageUuid;
use Functions\Validator;
use viceNet\EmailVerification;
use viceNet\User;

SessionManager::start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'login':
            handleLogin();
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }

} else {
    ManageResponse::handleInvalidRequest();
}

function handleLogin() : void {
    if (!isLoginFieldsAreEmpty()) {
        $username = Validator::validateInput($_POST['username']);
        $password = Validator::validateInput($_POST['password']);

        $loginData = compact('username', 'password');

        $login = new User();

        $result = $login->checkAuthentication( $loginData);

        switch ($result) {
            case 'user not found':
                handleUserNotFound();
                break;
            
            case 'wrong password':
                handleWrongPassword();
                break;

            case 'pending':
                handleVerificationPending( $login, $username);
                break;

            case 'verified':
                handleLoginSuccess( $login, $username);
                break;

            case 'blocked':
                handleBlockedUser();
                break;

            default:
                handleUnknownError();
                break;
        }

        $response = ['status' => true];
        ManageResponse::sendResponse( $response);
    }
}


function isLoginFieldsAreEmpty() : bool {
    if (empty($_POST['username']) || empty($_POST['password']) ) {
        SessionManager::setMessage('Please fill in all required fields!');
        $response = ['status' => false , 'error' => 'Please fill in all required fields!'];
        ManageResponse::sendResponse( $response);
    }
    return false;
}

function handleUserNotFound() {
    SessionManager::setMessage("Oops! We couldn't find that username. Please double-check and try again.");
    $response = ["status" => false , "error" => "Oops! We couldn't find that username. Please double-check and try again."];
    ManageResponse::sendResponse( $response);
}

function handleWrongPassword() {
    SessionManager::setMessage("Oops! Incorrect password. Please ensure your password is correct and try again.");
    $response = ['status' => false , 'error' => "Oops! Incorrect password. Please ensure your password is correct and try again." ];
    ManageResponse::sendResponse( $response);
}

function handleVerificationPending( User $login, $username) {
    $email = $login->getUserEmail( $username);

    $mail = new EmailVerification();
    
    if ($mail->sendVerificationEmail( $email)) {
        SessionManager::setRedirect('verification');
        SessionManager::set('verificationEmail',$email);
        $response = ['status' => true , 'email' => $email];
        ManageResponse::sendResponse( $response);
    } else {
        SessionManager::setMessage("Uh-oh! Login failed. Please check your credentials and try again. If the issue persists, contact support for assistance.");
        $response = ['status' => false , 'error' => "Uh-oh! Login failed. Please check your credentials and try again. If the issue persists, contact support for assistance."];
        ManageResponse::sendResponse( $response);
    }
}

function handleLoginSuccess(User $login, $username): void {
    $profileSetupStatus = $login->getProfileSetupStatus($username);

    // Get Session Token
    $tokenData = $login->getSessionToken($username);

    SessionManager::set('sessionToken', $tokenData['sessionToken']);
    SessionManager::set('userID', $tokenData['userID']);

    $redirectPath = $profileSetupStatus ? 'home' : 'setup';
    SessionManager::setRedirect($redirectPath);

    $response = ['status' => true];
    ManageResponse::sendResponse($response);
}

function handleBlockedUser() {
    SessionManager::setMessage("Your account has been blocked. Please contact support for further assistance.");
    $response = ['status' => false , 'error' => 'Your account has been blocked. Please contact support for further assistance.'];
    ManageResponse::sendResponse( $response);
}

function handleUnknownError() {
    SessionManager::setMessage("Unexpected error. Please try again later. If the issue persists, contact support for assistance.");
    $response = ['status' => false , 'error' => "Unexpected error. Please try again later. If the issue persists, contact support for assistance."];
    ManageResponse::sendResponse($response);
}