<?php
namespace ViceNet\Classes;

use Exception;
require_once __DIR__."/../../functions/profileSetupErrorChecker.php";
require_once __DIR__."/../../functions/redirectFunction.php";
require_once __DIR__."/../../functions/InputSanitizer.php";


class ProfileFunction {
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    // Log the error message to user_signup_log.txt
    private function logFunctionError($e): void
    {
        // Construct a meaningful error message
        $error_message = "Error saving user data: " . $e->getMessage();
    
        // Define the error log file path (use an absolute path)
        $error_log = __DIR__ . '/var/log/signup.txt';
    
        // Log the error with a timestamp
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[$timestamp] $error_message" . PHP_EOL;
    
        // Use error handling to prevent potential errors during logging
        set_error_handler(function () {});
        file_put_contents($error_log, $log_entry, FILE_APPEND);
        restore_error_handler();
    
        // Redirect to the error page
        $error_page = 'error.php'; // Provide the relative path to the error page
    
        // Ensure the error page exists before redirecting
        if (file_exists($error_page)) {
            header("Location: $error_page");
        }
    }

    public function updateUserProfile(int $userId, string $profilePic = null, string $coverPic = null, string $home = null, string $contact = null, string $education = null, string $employment = null, string $relationship_status = null, string $hobbies = null) : bool
    {
        try {
            $profile_setup_status = 1;
            // Construct the SQL statement
            $sql = "UPDATE user_profile SET";
            if (!is_null($userId)) {
                $sql .= "user_id = :user_id, ";
            }
            if (!is_null($profilePic)) {
                $sql .= "profile_pic = :profile_pic, ";
            }
            if (!is_null($coverPic)) {
                $sql .= "cover_pic = :cover_pic, ";
            }
            if (!is_null($home)) {
                $sql .= "home = :home, ";
            }
            if (!is_null($contact)) {
                $sql .= "contact = :contact, ";
            }
            if (!is_null($education)) {
                $sql .= "education = :education, ";
            }
            if (!is_null($employment)) {
                $sql .= "home = :home, ";
            }
            if (!is_null($relationship_status)) {
                $sql .= "relationship_status = :relationship_status, ";
            }
            if (!is_null($hobbies)) {
                $sql .= "hobbies = :hobbies, ";
            }
            if (!is_null($profile_setup_status)) {
                $sql .= "profile_setup_status = :profile_setup_status, ";
            }
            // Remove the trailing comma and add the WHERE clause to specify the record to update
            $sql = rtrim($sql, ', ') . " WHERE user_id = :user_id;";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':profile_pic', $profilePic);
            $stmt->bindParam(':cover_pic', $coverPic);
            $stmt->bindParam(':home', $home);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':education', $education);
            $stmt->bindParam(':employment', $employment);
            $stmt->bindParam(':relationship_status', $relationship_status);
            $stmt->bindParam(':hobbies', $hobbies);
            $stmt->bindParam(':profile_setup_status', $profile_setup_status);
            $stmt->bindParam(':user_id', $userId);
            
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
            return false;
        }
    }
    
}