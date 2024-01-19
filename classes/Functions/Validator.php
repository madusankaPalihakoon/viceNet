<?php
namespace Functions;

class Validator {

    public static function validateInput($data) {
        return htmlspecialchars(trim($data));
    }

    public static function validateEmail($email) {
        if (filter_var( $email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        } else {
            return null;
        }
    }

    public static function checkEmailIsNull( $email) : bool {
        if (is_null($email)) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePassword($password, $minLength = 8) {
        return strlen($password) >= $minLength;
    }

    public static function validateConfirmPassword($password, $confirmPassword) {
        return ($password == $confirmPassword);
    }

    public static function validateBoolean( $bool){
        return filter_var($bool, FILTER_VALIDATE_BOOLEAN);
    }   
}
