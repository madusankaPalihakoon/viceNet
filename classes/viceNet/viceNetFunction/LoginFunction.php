<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;
SessionManager::start();

class LoginFunction {
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
            ErrorLogger::logError(null, $e, 'login');
            return false;
        }
    }

    public function checkAuthentication(array $loginData): string {
        $sql = "SELECT Username, Email, PasswordHash, VerificationState FROM users WHERE Username = :username OR Email = :username;";
        $stmt = $this->executeStatement($sql, [':username' => $loginData['username']]);
    
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        if ($user) {
            if (!$this->verifyPassword($loginData['password'], $user['PasswordHash'])) {
                return 'wrong password';
            }
    
            $verificationState = $user['VerificationState'];
            $currentVerificationStatus = $this->handleVerificationStatus($verificationState);
    
            return $currentVerificationStatus;
        } else {
            return 'user not found';
        }
    }    
        
    private function verifyPassword(string $userPassword, string $hashedPassword): bool {
        return password_verify($userPassword, $hashedPassword);
    }

    private function handleVerificationStatus( $verificationState) : string {
        switch ($verificationState) {
            case 0:
                return 'pending';
                break;
            case 1:
                return 'verified';
                break;
            default:
                return 'blocked';
                break;
        }
    }

    public function getSessionToken($username): ?array {
        $sql = "SELECT userID, Email, SessionToken FROM users WHERE username = :username OR email = :username;";
    
        $stmt = $this->executeStatement($sql, [':username' => $username]);
    
        if ($stmt !== false) {
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if (!empty($data)) {
                $sessionToken = $data['SessionToken'];
                $email = $data['Email'];
                $userID = $data['userID'];
    
                return compact('sessionToken', 'email', 'userID');
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getUserEmail($username): string {
        $sql = "SELECT Email FROM users WHERE Username = :username OR Email = :username;";
    
        $stmt = $this->executeStatement($sql, [':username' => $username]);
    
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $email = $userData['Email'];
        
        return $email;
    }

    public function getProfileSetupStatus($username) : string {
        $sql = "SELECT u.Username, p.ProfileStatus FROM users u JOIN profile p ON u.userID = p.userID WHERE u.Username = :username ;";
    
        $stmt = $this->executeStatement($sql, [':username' => $username]);

        $profileData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $setupStatus = $profileData['ProfileStatus'];
    
        return $setupStatus;
    }
}