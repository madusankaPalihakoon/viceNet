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

    public function getFriend($sessionUser){
        $sql = "SELECT
        u.Name,
        p.ProfileImg,
        p.ProfileID,
        p.UserID,
        r.Status,
        CASE
            WHEN r.SenderID = :UserID THEN 'sent'
            WHEN r.ReceiverID = :UserID THEN 'received'
            ELSE NULL
        END AS Direction
    FROM
        Users u
    LEFT JOIN
        Profile p ON u.UserID = p.UserID
    LEFT JOIN
        Requests r ON u.UserID = r.SenderID OR u.UserID = r.ReceiverID
    WHERE
        u.UserID <> :UserID;
    ";
        $bindings = [':UserID' => $sessionUser];
        $stmt = $this->executeStatement($sql,$bindings);

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

    private function checkCurrentRequestStatus($requestUser, $sessionUser) {
        $sql = "SELECT Status FROM `requests` WHERE SenderID = :UserID AND ReceiverID = :FriendID;";
        $bindings = [':UserID' => $sessionUser, ':FriendID' => $requestUser];
    
        $stmt = $this->executeStatement($sql, $bindings);
    
        if ($stmt !== false && $stmt->rowCount() > 0) {
            return $stmt->fetchColumn(\PDO::FETCH_DEFAULT);
        } else {
            return null;
        }
    }    

    public function sendRequest( $requestUser, $sessionUser){
        $currentRequestStatus = $this->checkCurrentRequestStatus($requestUser, $sessionUser);

        if(is_null($currentRequestStatus)) {
            $sql = "INSERT INTO `requests`(`SenderID`, `ReceiverID`) VALUES (:SenderID, :ReceiverID)";
            $bindings = [ ':SenderID' => $sessionUser, ':ReceiverID' => $requestUser];

            return (bool) $this->executeStatement($sql,$bindings);
        }

        if($currentRequestStatus === 'pending') {
            $sql = "DELETE FROM `requests` WHERE SenderID = :SenderID AND ReceiverID = :ReceiverID;";
            $bindings = [ ':SenderID' => $sessionUser, ':ReceiverID' => $requestUser];

            return (bool) $this->executeStatement($sql,$bindings);
        }

        return $currentRequestStatus;
    }

    private function deleteCurrentRequest($sender, $receiver) {
        $sql = "DELETE FROM `requests` WHERE SenderID = :SenderID AND ReceiverID = :ReceiverID;";
        $bindings = [ ':SenderID' => $sender, ':ReceiverID' => $receiver];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    private function createFriendship($user, $friend) {
        $sql = "INSERT INTO `friends`(`UserID`, `FriendID`) VALUES (:UserID, :FriendID)";
        $bindings = [':UserID' => $user, ':FriendID'=> $friend];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    public function responseRequest($sender, $receiver) {
        return (bool) ($this->deleteCurrentRequest($sender, $receiver) && $this->createFriendship($sender, $receiver));
    }
}