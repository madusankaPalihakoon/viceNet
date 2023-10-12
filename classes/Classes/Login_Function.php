<?php
namespace ViceNet\Classes;

use Exception;
require_once __DIR__."/../../functions/signupErrorChecker.php";
require_once __DIR__."/../../functions/redirectFunction.php";
require_once __DIR__."/../../functions/InputSanitizer.php";

class LoginFunction {
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Log the error message to user_signup_log.txt
    private function logFunctionError($e) {
        // Define the error log file path using an absolute path
        $error_log = __DIR__ .'/../../var/log/signup.log';
        
        $log_entry = $e->getMessage();
    
        $log_entry .= PHP_EOL;
    
        // Log the error using error_log, which handles logging to the specified file
        error_log($log_entry, 3, $error_log);
    
        header("Location: ../pages/error");
        exit;
    }

    public function checkUsernameOrEmailCorrect(string $usernameOrEmail): bool {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :usernameOrEmail OR username = :usernameOrEmail;");
            $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            // Log the exception for debugging
            $this->logFunctionError("PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function getUserIdFromDatabase( String $usernameOrEmail) : ?int {
        try {
            $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE email = :usernameOrEmail OR username = :usernameOrEmail;");
            $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Check if a result was found
            if ($row && !empty($row['user_id'])) {
                // Return the name as a string
                return $row['user_id'];
            } else {
                // No result found or name is empty
                return null;
            }
        } catch (\PDOException $e) {
            // Log the exception for debugging
            $this->logFunctionError("PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function checkAuthenticationIsCorrect( int $userId, string $password) : bool {
        try {
            $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE user_id = :userId;");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Check if a result was found
            if ($row && !empty($row['password_hash'])) {
                $passwordHash = $row['password_hash'];
                if (password_verify($password, $passwordHash)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // No result found or name is empty
                return null;
            }
        } catch (\PDOException $e) {
            // Log the exception for debugging
            $this->logFunctionError("PDOException: " . $e->getMessage());
            return false;
        } 
    }

    public function getVerificationStateFromDatabase(int $userId) : ?float
    {
        try {
            $stmt = $this->pdo->prepare("SELECT is_verified FROM users WHERE user_id = :userId;");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            // Check if a result was found
            if ($row !== false) {
                // Check if 'is_verified' is not empty
                if (!is_null($row['is_verified'])) {
                    return $row['is_verified'];
                } else {
                    return null;
                }
            } else {
                return null; // No result found
            }
        } catch (\PDOException $e) {
            // Log the exception for debugging
            $this->logFunctionError($e);
            return false;
        }
    }    

    public function getUserEmailFromDatabase( int $userId) : ?String {
        try {
            $stmt = $this->pdo->prepare("SELECT email FROM users WHERE user_id = :userId;");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Check if a result was found
            if ($row && !empty($row['email'])) {
                return $row['email'];
            } else {
                return null;
            }
        } catch (\PDOException $e) {
            // Log the exception for debugging
            $this->logFunctionError($e);
            return false;
        }
    }
}