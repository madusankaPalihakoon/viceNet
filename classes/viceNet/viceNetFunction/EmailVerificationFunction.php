<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Functions\ErrorLogger;

class EmailVerificationFunction {
    const TIME_THRESHOLD_SECONDS = 300; // 5 minutes
    private $con;
    private $pdo;

    public function __construct() {
        $this->con = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $this->pdo = $this->con->connect();
    }

    private function executeStatement($sql, $bindings = []) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bindings);

            $this->pdo->commit();
            return $stmt;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            ErrorLogger::logError($sql, $e, 'verification');
            return false;
        }
    }

    public function saveVerificationCode( $email, $hashData) : bool {
        $AttemptCount = 0;
        $sql = "UPDATE verification v
                JOIN users u ON v.userID = u.userID
                SET v.VerificationCodeHash = :verificationCodeHash,
                    v.Salt = :salt,
                    v.AttemptsCount = :AttemptsCount
                WHERE u.Email = :Email";
        $bindings = array(
            ':verificationCodeHash' => $hashData['hashedVerificationCode'],
            ':salt' => $hashData['salt'],
            ':AttemptsCount' => $AttemptCount,
            ':Email' => $email
        );

        return $stmt = (bool) $this->executeStatement( $sql, $bindings);
    }

    public function verifyEmail($email,$code) :string {
        // get saved verification data using email
        $verificationData = $this->getVerificationData( $email);

        // check verificationData is null
        if (is_null( $verificationData)){
            return 'no data';
        }

        // Check verification attempts is over
        $currentCount = $verificationData['AttemptsCount'];
        if ($this->isVerificationAttemptsOver( $currentCount)) {
            $this->markEmailAsBlocked( $email);
            return 'maximum attempts try';
        }

        // Increment Count
        // $this->incrementCurrentCount( $email);

        // check verification code
        $hashCode = $verificationData['VerificationCodeHash'];
        $salt = $verificationData['Salt'];
        if(!$this->authVerificationCode($code, $hashCode, $salt)) {
            return 'wrong';
        }

        // check verification code expire or not
        $sentTime = $verificationData['SentTime'];
        if(!$this->isVerificationCodeExpired( $sentTime)) {
            return 'expire';
        }

        // Mark Email is Verified
        If ( $this->markEmailAsVerified( $email)){
            return 'true';
        }
    }

    private function getVerificationData( $email) :?array {
        $sql = "SELECT v.VerificationCodeHash,
                       v.Salt,
                       v.AttemptsCount,
                       v.SentTime
                FROM users u
                JOIN verification v ON u.userID = v.userID
                WHERE u.Email = :email;";
        $bindings = [':email' => $email];

        $stmt = $this->executeStatement( $sql, $bindings);

        if($stmt !== false) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    private function isVerificationAttemptsOver( $currentCount) {
        return $currentCount >= 3;
    }

    private function markEmailAsBlocked( $email) {
        $verificationState = 2;

        $sql = "UPDATE users
                SET VerificationState = :VerificationState
                WHERE Email = :Email;";

        $bindings = [':VerificationState' => $verificationState , ':Email' => $email];

        $this->executeStatement( $sql,$bindings);
    }

    private function incrementCurrentCount( $email) {
        $sql = "UPDATE verification v
                JOIN users u ON v.userID = u.userID
                SET v.AttemptsCount = v.AttemptsCount + 1
                WHERE u.Email = :Email";
    
        $bindings = [':Email' => $email];

        $this->executeStatement( $sql, $bindings);
    }

    private function authVerificationCode($code, $hashCode, $salt) :bool {
        $generatedHashed = hash('sha256', $code . $salt);
        return hash_equals($hashCode,$generatedHashed);
    }

    private function isVerificationCodeExpired( $sentTime) :bool {
        try {
            // set timezone to sri lanka(Because server location is sri lanka)
            date_default_timezone_set('Asia/Colombo');

            // convert sent time string to time
            $codeSentTime = strtotime($sentTime);
            if ($codeSentTime !== false) {
                // current timestamp
                $currentTime = time();

                // calculate time difference
                $timeDifference = $currentTime - $codeSentTime;

                // check time difference is grater than 5 min
                return $timeDifference < self::TIME_THRESHOLD_SECONDS;
            }
                return false;
        } catch (\Throwable $th) {
            ErrorLogger::logError(null,$th,'verification');
            return false;
        }
    }

    private function markEmailAsVerified( $email) {
        $verificationState = 1;

        $sql = "UPDATE users
                SET VerificationState = :VerificationState
                WHERE Email = :Email";

        $bindings = [':VerificationState' => $verificationState , ':Email' => $email];

        return (bool) $this->executeStatement( $sql, $bindings);
    }
}