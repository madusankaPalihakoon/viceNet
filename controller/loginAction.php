<?php
// Enable error reporting for development
error_reporting(E_ALL);

require_once __DIR__."/../functions/InputSanitizer.php";
require_once __DIR__."/../functions/redirectFunction.php";
require_once __DIR__."/../config/SessionConfig.php";
require_once __DIR__."/../classes/Classes/User.php";
use ViceNet\Classes\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = sanitizeString($_POST['usernameOrPassword']); // Assuming 'name' is the form field name
    $password = $_POST['pword']; // Assuming 'pword' is the form field name

    if (empty($usernameOrEmail) || empty($password)) {
        redirectTo("login", ["login_error" => "Please fill out all required fields."]);
    } else {
        handleLogin($usernameOrEmail, $password);
    }
} else {
    echo $_SERVER['REQUEST_METHOD'];
}

function handleLogin($usernameOrEmail, $password): void {
    try {
        $user = new User();

        if (!$user->isValidUsernameOrEmail($usernameOrEmail)) {
            redirectTo("login", ["login_error" => "Invalid username or email."]);
            return;
        }

        $userId = $user->getUserId($usernameOrEmail);
        $_SESSION['session_user_id'] = $userId;

        if (!$user->isValidAuthentication($userId, $password)) {
            // Invalid Password
            redirectTo("login", ["login_error" => "Invalid Password"]);
            return;
        }

        $verificationState = $user->getVerificationState( $_SESSION['session_user_id']);

        if (null !== $verificationState) {
            switch ($verificationState) {
                case '0':
                    hadleSendVerificationCode( $userId);
                    break;
                case '1':
                    handleloginSuccess( $userId);
                    break;
                default:
                    echo 'blocked';
                    break;
            }
        }
    } catch (\Throwable $th) {
        // Handle exceptions
        errorLog($th); // log exceptions
        redirectTo("login", ["login_error" => $th]);
        // redirectTo("login", ["login_error" => "An error occurred during login."]);
    }
}

function hadleSendVerificationCode( int $userId) {
    try {
        $user = new User();
        $email = $user->getUserEmail( $userId);
        $user->resendVerificationCodeToUser( $email);
        // Redirect to email verification page
        redirectTo("verify-email", ['verification_email' => $email]);
    } catch (\Throwable $th) {
        // Handle exceptions appropriately (e.g., log the error)
        redirectTo("login", ["login_error" => "An error occurred during login."]);
    }
}

function handleloginSuccess( int $userId) {
    // Destroy the current session
    session_destroy();
    // Start session
    session_start();
    redirectTo("profile_setup",['session_id' => $userId]);
}

function handleBlockedEmail() {
    $user = new User();
    redirectTo('error',['error' => 'your email is blocked!']);
}
