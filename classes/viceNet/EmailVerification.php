<?php

namespace viceNet;

use Functions\ErrorLogger;
use Functions\Mail;
use viceNetFunction\EmailVerificationFunction;

class EmailVerification {
    private $mailer;
    private $emailVerificationFunction;

    public function __construct() {
        $this->mailer = new Mail();
        $this->emailVerificationFunction = new EmailVerificationFunction();
    }
    
    private function generateVerificationCode() : int {
        // Generate a random 4-digit verification code
        return rand(1000, 9999);
    }
    
    private function hashedVerificationCode($code) : array {
        // Generate a random salt
        $salt = bin2hex(random_bytes(16));
        // Combine the verification code and salt, then hash using SHA-256
        $hashedVerificationCode = hash('sha256', $code . $salt);
        // Return an array containing the hashed verification code and the salt
        return $hashData = array('hashedVerificationCode' => $hashedVerificationCode, 'salt' => $salt,);
    }

    private function saveVerificationCode( string $email, array $hashData) : bool {
        return $this->emailVerificationFunction->saveVerificationCode( $email, $hashData);
    }

    public function sendVerificationEmail( $email) : bool {
        try {
            $code = $this->generateVerificationCode();
            $hashData = $this->hashedVerificationCode( $code);
            return ($this->mailer->sendVerificationCode($email, $code) && $this->saveVerificationCode($email, $hashData));
        } catch (\Throwable $th) {
            ErrorLogger::logError(null, $th,'verification');
            return false;
        }
    }

    public function verifyEmail($email, $code) {
        return $this->emailVerificationFunction->verifyEmail( $email, $code);
    }
}
