<?php

namespace viceNet;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

use Functions\Mail;
use viceNetFunction\LoginFunction;
use viceNetFunction\SignupFunction;
use viceNetFunction\VerificationFunction;

class User {
    private $signupFunction;
    private $loginFunction;
    private $mailer;

    public function __construct() {
        $this->signupFunction = new SignupFunction();
        $this->loginFunction = new LoginFunction();
        $this->mailer = new Mail();
    }

    // signup functions

    // Returns 'true' if the username already exists in the database, otherwise returns 'false'
    public function isUsernameTaken( string $username) : bool {
        return $this->signupFunction->checkUsernameIsTaken( $username);
    }
    // Returns 'true' if the email already exists in the database, otherwise returns 'false'
    public function isEmailTaken( string $email) : bool {
        return $this->signupFunction->checkEmailIsTaken( $email);
    }
    // Save user data to database
    public function setUser( array $signupData) : bool {
        return $this->signupFunction->setUser( $signupData);
    }

    public function getUser() : void {}

    public function updateUser() : void {}

    public function removeUser() : void {}


    // Login function
    public function checkAuthentication( array $loginData) : ?string {
        return $this->loginFunction->checkAuthentication( $loginData);
    }

    public function getSessionToken( $username) : array {
        return $this->loginFunction->getSessionToken( $username);
    }

    public function getUserEmail( $username) : string {
        return $this->loginFunction->getUserEmail( $username);
    }

    public function getProfileSetupStatus( $username) : bool {
        return $this->loginFunction->getProfileSetupStatus( $username);
    }
}
