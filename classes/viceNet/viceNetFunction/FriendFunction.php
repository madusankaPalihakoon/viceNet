<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;

SessionManager::start();

class FriendFunction {
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
            ErrorLogger::logError($sql, $e, 'friend');
            return false;
        }
    }

    public function getFriend(){
        $sql = "SELECT
                    u.Name,
                    p.ProfileImg,
                    p.ProfileID,
                    u.UserID
                FROM
                    Users u
                JOIN
                    Profile p ON u.UserID = p.UserID;";
        $stmt = $this->executeStatement($sql);

        if($stmt !== false) {
            $friendsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($friendsData as &$friend) {
                $userPath = $friend['UserID'];
    
                $key = 'profilePath';
                $path = "../uploads/$userPath/profile";
    
                $friend[$key] = $path;
            }
            unset($friend);
    
            return $friendsData;
        } else {
            return [];
        }
    }

    public function sendRequest( $profileID){
        $sql = "INSERT INTO `friends`(`UserID`, `FriendID`, `requestStatus`) VALUES (:UserID,)";
    }
}