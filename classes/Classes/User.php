<?php
namespace ViceNet\Classes;
require_once __DIR__."/../Database/databaseCon.php";
use ViceNet\Database\PDO_Config;
require __DIR__.'/Signup_Function.php';
require __DIR__.'/Login_Function.php';
require __DIR__.'/Profile_Function.php';

class User {
    private $signupFunction;
    private $loginFunction;
    private $profileFunction;

    public function __construct()
    {
        $database = new PDO_Config();
        $pdo = $database->getConnection(); // Get the PDO instance 
        $this->signupFunction = new SignupFunction($pdo);
        $this->loginFunction = new LoginFunction($pdo);
        $this->profileFunction =  new ProfileFunction($pdo);
    }

    public function checkEmailInUse(string $email) : bool 
    {
        if ($this->signupFunction->isEmailInUse($email)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function checkUsernamelInUse($username) : bool 
    {
        if ($this->signupFunction->isUsernameInUse($username)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function signupUser(string $name, string $email, string $username, string $country, string $pnumber, string $birthday, string $gender, string $password): void
    {
        $this->signupFunction->saveUserToDatabase ($name, $email, $username, $country, $pnumber, $birthday, $gender, $password);
    }

    // Send a verification code
    public function sendVerificationCode(string $email): bool
    {
        $verificationCode = rand(1000, 9999);
        if ($this->signupFunction->sendVerificationCodeAndSaveToDatabase($email, $verificationCode)) {
            return true;
        }
        else{
            return false;
        }
    }

    // resend verification code
    public function resendVerificationCodeToUser($email) : bool 
    {
        $verificationCode = rand(1000, 9999);
        if ($this->signupFunction->resendVerificationCode($email, $verificationCode)) {
            return true;
        }
        else{
            return false;
        }
    }

    // Check if the verification code is correct
    public function isVerificationCodeValid(string $email, int $verificationCode): bool
    {
        if ($this->signupFunction->checkVerificationCodeIsValid($email, $verificationCode)) {
            return true;
        }
        else{
            return false;
        }
    }

    // update verification state on database 0 for pending verification 1 for verification succsess and 2 for block user
    public function updateVerificationState( String $email, INT $verification_state) : bool 
    {
        if ($this->signupFunction->updateVerificationStateInDatabase($email, $verification_state)){
            return true;
        } else {
            return false;
        }
    }

    // get user
    public function getUserName(string $email): ?string {
        $name = $this->signupFunction->getUserFromDatabase($email);
        
        if ($name !== null) {
            return $name;
        }
    }

    // check entered username or email is valid
    public function isValidUsernameOrEmail( string $usernameOrEmail): bool {
        if ($this->loginFunction->checkUsernameOrEmailCorrect( $usernameOrEmail))
        {
            return true;
        }else
        {
            return false;
        }
    }

    // get user id from database using email or username
    public function getUserId( string $usernameOrEmail) : ?int {
        $userId = $this->loginFunction->getUserIdFromDatabase( $usernameOrEmail);
        return $userId;
    }

    // check password is correct
    public function isValidAuthentication( int $userId, string $password) : bool {
        if ($this->loginFunction->checkAuthenticationIsCorrect( $userId, $password)) {
            return true;
        } else {
            return false;
        }
    }

    public function getVerificationState( int $userId) {
        return $this->loginFunction->getVerificationStateFromDatabase( $userId);
    }

    public function getUserEmail( int $userId) : String {
        return $this->loginFunction->getUserEmailFromDatabase( $userId);
    }

    public function profileSetup(int $userId, string $profilePic = null, string $coverPic = null, string $home_town = null, string $contact_info = null, string $education = null, string $employment = null, string $relationship_status = null, string $hobbies = null) : bool {
        return $this->profileFunction->updateUserProfile( $userId, $profilePic, $coverPic, $home_town, $contact_info, $education, $employment, $relationship_status, $hobbies );
    }
}
