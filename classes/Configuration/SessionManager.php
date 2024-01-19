<?php

namespace Configuration;

require_once __DIR__.'/../../vendor/autoload.php';

use Functions\ErrorLogger;
use Functions\Validator;
use viceNet\User;

class SessionManager {
    // Set a unique identifier for the session
    private const SESSION_NAME = 'viceNet';

    // Ensure that the session cookie is only sent over HTTPS
    private const SECURE_COOKIE = true;

    // Set the session cookie to be accessible only through the HTTP protocol (not JavaScript)
    private const HTTP_ONLY_COOKIE = true;

    // Set the session cookie to be secure, which means it will only be sent over secure connections (HTTPS)
    private const SESSION_USE_ONLY_COOKIES = true;

    // Set the session lifetime in seconds (e.g., 30 minutes)
    private const SESSION_LIFETIME = 1800;

    public static function start() {
        try {
            self::startSession();
        } catch (\Throwable $th) {
            ErrorLogger::logError(null, $th,'session');
        }
    }

    private static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(self::SESSION_NAME);
            session_set_cookie_params(
                self::SESSION_LIFETIME,
                '/',
                $_SERVER['SERVER_NAME'], // Change to SERVER_NAME for a more reliable host determination
                self::SECURE_COOKIE,
                self::HTTP_ONLY_COOKIE
            );

            if (session_start()) {
                // Regenerate the session ID periodically to help prevent session fixation attacks
                self::regenerateSession();
            }
        }
    }

    private static function regenerateSession() {
        if (!isset($_SESSION['last_regenerated']) || $_SESSION['last_regenerated'] < (time() - 900)) {
            session_regenerate_id(true);
            $_SESSION['last_regenerated'] = time();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }    

    public static function setMessage($message) {
        self::startSession();
        $_SESSION['message'] = $message;
    }

    public static function displayMessage() {
        self::startSession();
    
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
            return $message;
        }
    
        return '';
    }

    public static function setRedirect( $page) {
        self::startSession();

        $_SESSION['page'] = $page;
    }

    public static function sendRedirectPage() {
        self::startSession();
    
        if (isset($_SESSION['page'])) {
            $page = $_SESSION['page'];
            unset($_SESSION['page']);
            return $page;
        }
    
        return '';
    }

    public static function getSessionUser() {
        self::startSession();
    
        if (!self::compareSessionToken() && !self::checkSessionUser()) {
            return null;
        }

        return self::get('userID');
    }
    
    private static function compareSessionToken(): bool {
        if (isset($_SESSION['SessionToken'])) {
            $username = Validator::validateEmail($_SESSION['email']);
            $user = new User();
            $sessionData = $user->getSessionToken($username);
    
            return $sessionData['sessionToken'] === $_SESSION['sessionToken'];
        }
    
        return false;
    }
    
    private static function checkSessionUser(): bool {
        return null !== self::get('userID');
    }    
}
