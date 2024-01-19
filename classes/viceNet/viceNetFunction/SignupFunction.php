<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;
SessionManager::start();

class SignupFunction {
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
            ErrorLogger::logError(null, $e, 'signup');
            return false;
        }
    }

// Returns 'true' if the username already exists in the database, otherwise returns 'false'
    public function checkUsernameIsTaken($username): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username;";

        $stmt = $this->executeStatement($sql, [':username' => $username]);

        return ($stmt !== false) ? $stmt->fetchColumn() == 1 : false;
    }

// Returns 'true' if the username already exists in the database, otherwise returns 'false'
    public function checkEmailIsTaken($email): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email;";

        $stmt = $this->executeStatement($sql, [':email' => $email]);

        return ($stmt !== false) ? $stmt->fetchColumn() == 1 : false;
    }

// Save User Information to database
    public function setUser(array $signupData): bool {
        $sql = "INSERT INTO 
            `users`(`UserID`,`Name`, `Email`, `Username`, `Country`, `Phone`, `Birthday`, `Gender`, `PasswordHash`,`SessionToken`) 
            VALUES (:userID, :name, :email, :username, :country, :phone, :birthday, :gender, :passwordHash, :sessionToken);";

        $bindings = [
            ':userID' => $signupData['UserID'],
            ':name' => $signupData['Name'],
            ':email' => $signupData['Email'],
            ':username' => $signupData['Username'],
            ':country' => $signupData['Country'],
            ':phone' => $signupData['Phone'],
            ':birthday' => $signupData['Birthday'],
            ':gender' => $signupData['Gender'],
            ':passwordHash' => $signupData['PasswordHash'],
            ':sessionToken' => $signupData['SessionToken']
        ];

        return (bool) $this->executeStatement($sql, $bindings);
    }

}