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

    public function updateUserProfile(int $userId, string $profilePic, string $coverPic, string $home, int $contact, string $education, string $employment, string $relationship_status, string $hobbies) : bool
    {
        try {
            $profile_setup_status = 1;
            
            $stmt = $this->pdo->prepare("UPDATE user_profile SET profile_pic = :profile_pic, cover_pic = :cover_pic, home = :home, contact = :contact, education = :education, employment = :employment, relationship_status = :relationship_status,hobbies = :hobbies, profile_setup_status = :profile_setup_status WHERE user_id = :user_id;");
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