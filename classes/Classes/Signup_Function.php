<?php
namespace ViceNet\Classes;

use Exception;
require_once __DIR__."/../../functions/signupErrorChecker.php";
require_once __DIR__."/../../functions/redirectFunction.php";
require_once __DIR__."/../../functions/InputSanitizer.php";


class SignupFunction {
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

    
    // Checking whether an email is already in use
    public function isEmailInUse(string $email): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($user)) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }
    // Checking whether an Username is already in use
    public function isUsernameInUse(string $username): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($user)) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }
    // Implementation of saving user data to the database
    public function saveUserToDatabase(string $name, string $email, string $username, string $country, string $pnumber, string $birthday, string $gender, string $password): void
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $is_verified = 0;
            $stmt = $this->pdo->prepare("INSERT INTO users(name,email,username,country,phone_number, birthday, gender, password_hash, is_verified) VALUES (:name, :email, :username, :country, :p_number, :birthday, :gender, :password, :is_verified)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':p_number', $pnumber);
            $stmt->bindParam(':birthday', $birthday);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':is_verified', $is_verified);
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }

    // Sending verification code
    public function sendVerificationCodeAndSaveToDatabase(string $email, int $verificationCode): bool
    {
        try {
            if (!$this->updateVerificationCodeInDatabase($email, $verificationCode) || 
                !$this->sendVerificationEmail($email, $verificationCode)) {
                redirectTo('error', 'Invalid email', 'Something went wrong');

                return false;
            }
            
                return true;
        } catch (Exception $e) {
            $this->logFunctionError($e);
            return false;
        }
    }

    // update verification code on database
    private function updateVerificationCodeInDatabase(string $email, int $verificationCode): bool
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE email_verification AS e JOIN users AS u ON e.user_id = u.user_id SET e.verification_code = :verification_code WHERE u.email = :email;");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':verification_code', $verificationCode);
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

    // send verification from email
    private function sendVerificationEmail(string $email, int $verificationCode): bool
    {
        require_once __DIR__."/../../mailer/sendMail.php";

        if (send_email ( $email, $verificationCode)) {
            return true;
        }
        else {
            return false;
        }
    }

    // resend verification code
    public function resendVerificationCode(string $email, int $verificationCode): bool
    {
        try {
            if (!$this->updateVerificationCodeInDatabase($email, $verificationCode) || 
                !$this->sendVerificationEmail($email, $verificationCode)) {
                redirectTo('error', 'Invalid email', 'Something went wrong');

                return false;
            }
            
                return true;
        } catch (Exception $e) {
            $this->logFunctionError($e);
            return false;
        }
    }

    // check verification code is valid
    public function checkVerificationCodeIsValid(string $email, int $verification_code): bool {
        try {
            // Prepare the SQL query with placeholders
            $stmt = $this->pdo->prepare("SELECT ev.verification_code FROM email_verification ev INNER JOIN users u ON ev.user_id = u.user_id WHERE u.email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
            // Check if a result was found
            if ($row) {
                // Retrieve the stored verification code
                $stored_code = (int)$row['verification_code'];

                // Compare the entered code with the stored code
                if ($verification_code === $stored_code) {

                    // Get the verification code send time
                    $sendTime = $this->getVerificationCodeSendTime($email, $verification_code);

                    // Check if verification code is expired
                    if (!$this->IsVerificationCodeExpire($sendTime)) {
                        // Verification code is valid and not expired
                        return true;
                    }
                }
            }
            // Verification code does not match or email not found
            return false;
        } catch (\PDOException $e) {
            // Handle database exceptions and log them
            $this->logFunctionError($e);

            // Return false to indicate an error
            return false;
        }
    }

    // Check if the verification code has expired
    private function IsVerificationCodeExpire(string $dateTimeString): bool {
        try {
            // Set the timezone to Sri Lanka (you may adjust this to your specific timezone)
            date_default_timezone_set('Asia/Colombo');
        
            // Convert the verification code send time string to a Unix timestamp
            $verificationTime = strtotime($dateTimeString);
        
            if ($verificationTime !== false) {
                // Get the current Unix timestamp
                $currentTime = time();
        
                // Calculate the time difference in seconds
                $timeDifference = $currentTime - $verificationTime;
        
                // Check if the time difference is greater than 5 minutes (300 seconds)
                return $timeDifference > 300; // Code has expired
            }

            // Return true if the conversion fails or if it's not a valid timestamp
            return true; // Code has expired or an error occurred
        } catch (\Exception $e) {
            // Handle any exceptions that occur and log them
            $this->logFunctionError($e);

            // Return true to indicate an error or code expiration
            return true;
        }
    }

    // Get the verification code send time from the database
    private function getVerificationCodeSendTime(string $email, int $verification_code): ?string {
        try {
            $stmt = $this->pdo->prepare("SELECT ev.send_time FROM email_verification AS ev JOIN users AS u ON ev.user_id = u.user_id WHERE u.email = :email AND ev.verification_code = :verification_code ");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':verification_code', $verification_code);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Check if a result was found
            if ($row && !empty($row['send_time'])) {
                // Return the send_time as a string
                return $row['send_time'];
            } else {
                // No result found or send_time is empty
                return null;
            }
        }  catch (\PDOException $e) {
            // Handle database exceptions and log them
            $this->logFunctionError($e);

            // Return null to indicate an error
            return null;
        }
    }

    public function updateVerificationStateInDatabase(string $email, int $verification_state): bool {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET is_verified = :verification_state WHERE email = :email;");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':verification_state', $verification_state); // Fixed typo here
    
            if ($stmt->execute()) {
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

    public function getUserFromDatabase( string $email) : string {
        try {
            $stmt = $this->pdo->prepare("SELECT name FROM users WHERE email = :email;");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Check if a result was found
            if ($row && !empty($row['name'])) {
                // Return the name as a string
                return $row['name'];
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
}