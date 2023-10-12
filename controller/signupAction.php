<?php
require_once __DIR__."/../functions/InputSanitizer.php";
require_once __DIR__."/../functions/redirectFunction.php";
require_once __DIR__."/../config/SessionConfig.php";
require_once __DIR__."/../classes/Classes/User.php";
use ViceNet\Classes\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Check if user agreed to terms
    $agree = isset($_POST["agree"]);

    // Check if required fields are empty
    if (!$agree || empty($_POST["name"]) || empty($_POST["user_email"]) || empty($_POST["username"]) || empty($_POST["country"]) || empty($_POST["pnumber"]) || empty($_POST["birthday"]) || empty($_POST["gender"]) || empty($_POST["pword"]) || empty($_POST["confirmpword"])) {
        $errors["signup_error"] = "Please fill out all required fields.";
    } else {
        // Sanitize and validate each field
        $name = sanitizeString($_POST["name"]);
        $email = sanitizeString($_POST["user_email"]);
        $username = sanitizeString($_POST["username"]);
        $country = sanitizeString($_POST["country"]);
        $pnumber = sanitizeString($_POST["pnumber"]);
        $birthday = sanitizeString($_POST["birthday"]);
        $gender = sanitizeString($_POST["gender"]);
        $password = $_POST["pword"];
        $confirm_password = $_POST["confirmpword"];

        // Validate email
        if (!sanitizeEmail($email)) {
            $errors["signup_error"] = "Invalid Email address.";
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $errors["signup_error"] = "The password and confirm password fields do not match. Please make sure to enter the same password in both fields.";
        }
    }

    // Check errors
    if (empty($errors)) {
        try {
            $user = new User();
            if (!$user->checkEmailInUse( $email) && !$user->checkUsernamelInUse( $username)) {
                $user->signupUser($name, $email, $username, $country, $pnumber, $birthday, $gender, $password);
                // Send verification code to user email
                $user->sendVerificationCode($email);
                // Redirect to email verification page
                redirectTo("verify-email", ['verification_email' => $email]);
            }
            else {
                // Handle other exceptions
                $errors["signup_error"] = "Oops! The username or email you entered is already in use. Please choose a different username or use a different email address.";
                redirectTo("signup", ['signup_error' => $errors]);

            }
        } catch (Exception $e) {
            // Handle other exceptions
            $errors["signup_error"] = "An error occurred during sign up. Please try again later.";
            redirectTo("signup", ['signup_error' => $errors]);

        }
    } elseif (!empty($errors)) {
        redirectTo("signup", ['signup_error' => $errors]);
    }
}
unset($_SESSION['signup_error']);