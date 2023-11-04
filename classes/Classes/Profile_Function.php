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
        $error_log = __DIR__ . '/var/log/signup.log';
    
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
    public function updateUserProfile($userId, $profilePic, $coverPic, $home, $contact, $education, $employment, $relationship_status, $hobbies, $profile_setup_status): bool {
        $sqlAndParams = $this->generateUpdateUserProfileSql($userId, $profilePic, $coverPic, $home, $contact, $education, $employment, $relationship_status, $hobbies, $profile_setup_status);
    
        try {
            $stmt = $this->pdo->prepare($sqlAndParams['sql']);
            $stmt->execute($sqlAndParams['params']);
    
            return true;
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
            return false;
        }
    }
    
    private function generateUpdateUserProfileSql($userId, $profilePic, $coverPic, $home, $contact, $education, $employment, $relationship_status, $hobbies, $profile_setup_status): array {
        $sql = "UPDATE user_profile SET ";
        $params = [];
    
        $updateColumns = [
            'profile_pic' => $profilePic,
            'cover_pic' => $coverPic,
            'home' => $home,
            'contact' => $contact,
            'education' => $education,
            'employment' => $employment,
            'relationship_status' => $relationship_status,
            'hobbies' => $hobbies,
            'profile_setup_status' => $profile_setup_status,
        ];
    
        foreach ($updateColumns as $column => $value) {
            if (!is_null($value)) {
                $sql .= "$column = :$column, ";
                $params[":$column"] = $value;
            }
        }
    
        // Remove the trailing comma and add the WHERE clause to specify the record to update
        $sql = rtrim($sql, ', ') . " WHERE user_id = :user_id";
        $params[':user_id'] = $userId;
    
        return [
            'sql' => $sql,
            'params' => $params,
        ];
    }
    
}