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
        case 'signup':
            handleSignup();
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }

} else {

    ManageResponse::handleInvalidRequest();
}

//  Functions

function handleSignup() : void {
    try {
        if (!isSignupFieldsAreEmpty() &&  isAgreeTermCondition()){
            dataValidation();
        }
    } catch (\Throwable $th) {
        ErrorLogger::logError(null, $th, 'signup');
        ManageResponse::handleUnknownError();
    }
}

// Check input Fields are Empty
function isSignupFieldsAreEmpty() : bool {
    if ( empty($_POST['name']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['country']) || empty($_POST['phone']) || empty($_POST['birthday']) || empty($_POST['gender']) || empty($_POST['password']) || empty($_POST['confirmPassword']) ){
        SessionManager::setMessage('Please fill in all required fields!');
        $response = ['status' => false , 'error' => 'Please fill in all required fields!'];
        ManageResponse::sendResponse( $response);
    }

    return false;
}

// Check user agree the term of condition
function isAgreeTermCondition() : bool {
    if (!isset($_POST['termCheckbox'])){
        SessionManager::setMessage('Please agree Term before Signup!');
        $response = ['status' => false , 'error' => 'Please agree Term before Signup!'];
        ManageResponse::sendResponse( $response);
    }

    return true;
}

// Validate input Fields
function dataValidation() : void {
    $name = Validator::validateInput($_POST['name']);
    $email = Validator::validateEmail($_POST['email']);
    $username = Validator::validateInput($_POST['username']);
    $country = Validator::validateInput($_POST['country']);
    $phone = Validator::validateInput($_POST['phone']);
    $birthday = Validator::validateInput($_POST['birthday']);
    $gender = Validator::validateInput($_POST['gender']);
    $password = Validator::validateInput($_POST['password']);
    $confirmPassword = Validator::validateInput($_POST['confirmPassword']);

    if(Validator::checkEmailIsNull( $email)){
        SessionManager::setMessage('Invalid email address. Please enter a valid email and tray again!');
        $response = ['status' => false , 'error' => 'Invalid email address. Please enter a valid email and tray again!'];
        ManageResponse::sendResponse( $response);
    }

    if (!Validator::validateConfirmPassword($password , $confirmPassword)){
        SessionManager::setMessage('Password and Confirm Password do not match. Please tray again!');
        $response = ['status' => false , 'error' => 'Password and Confirm Password do not match. Please tray again!'];
        ManageResponse::sendResponse( $response);
    }

    if(!Validator::validatePassword( $password)){
        SessionManager::setMessage('Password must be at least 8 characters long!');
        $response = ['status' => false , 'error' => 'Password must be at least 8 characters long!'];
        ManageResponse::sendResponse( $response);
    }

    $user = new User();

    if ( $user->isUsernameTaken( $username)) {
        SessionManager::setMessage('Username is already in use. Please choose a different username!');
        $response = ['status' => false , 'error' => 'Username is already in use. Please choose a different username!'];
        ManageResponse::sendResponse( $response);
    }

    if ( $user->isEmailTaken( $email)){
        SessionManager::setMessage('Email is already in use. Please use a different email address!');
        $response = ['status' => false , 'error' => 'Email is already in use. Please use a different email address!'];
        ManageResponse::sendResponse( $response);
    }

    // Hashed password
    $hashPassword = password_hash( $password, PASSWORD_BCRYPT);

    // Generate a unique session token
    $sessionToken = bin2hex( random_bytes(32));

    // Generate unique ID for userID
    $UserID = ManageUuid::generateUUID();

    $signupData = array(
        "UserID" => $UserID,
        "Name" => $name,
        "Email" => $email,
        "Username" => $username,
        "Country" => $country,
        "Phone" => $phone,
        "Birthday" => $birthday,
        "Gender" => $gender,
        "PasswordHash" => $hashPassword,
        "SessionToken" => $sessionToken
    );

    if (!$user->setUser( $signupData)){
        SessionManager::setMessage('Unable to sign Up this time. Please try again later!');
        $response = ['status' => false , 'error' => 'Unable to sign Up this time. Please try again later!'];
        ManageResponse::sendResponse( $response);
    }

    // When set user is true, Send verification cde to user's Email
    $sendVerification = new EmailVerification();

    // Set session verification email, for use validation
    SessionManager::set('verificationEmail', $email);

    // Send Verification code
    if( !$sendVerification->sendVerificationEmail( $email)) {
        SessionManager::setMessage('Unable to send verification code. Please try again later!');
        $response = ['status' => false , 'error' => 'Unable to send verification code. Please try again later!'];
        ManageResponse::sendResponse( $response);
    }

    // When Verification code sent successfully
    SessionManager::setRedirect('verification');
    $response = ['status' => true , 'message' => 'sign up successfully'];
    ManageResponse::sendResponse( $response);
}

