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
    private function logFunctionError($e) {
        // Define the error log file path using an absolute path
        $error_log = __DIR__ . '/../../var/log/login.log';
    
        // Include additional information in the log entry (optional)
        $log_entry = '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
        
        // Append a new line to the log entry
        $log_entry .= PHP_EOL;
    
        // Log the error using error_log, which handles logging to the specified file
        error_log($log_entry, 3, $error_log);
    
        // Redirect to the error page
        $error_page = __DIR__.'/../../pages/error.php';
    
        // Ensure the error page exists before redirecting
        if (file_exists($error_page)) {
            header("Location: $error_page");
            exit;
        } else {
            // Handle the case where the error page does not exist
            // You can log this error separately or handle it in another way
            error_log("Error page not found: $error_page");
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