<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;
use LengthException;

SessionManager::start();

class LikeFunction {
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
            ErrorLogger::logError($sql, $e, 'post');
            return false;
        }
    }

    private function isUserAlreadyLike($userID, $postID) {
        $sql = "SELECT COUNT(*) AS LikeStatus FROM Likes WHERE PostID = :PostID AND UserID = :UserID;";

        $bindings = [':UserID' => $userID, ':PostID' => $postID];

        $stmt = $this->executeStatement($sql,$bindings);

        return $stmt !== false && $stmt->fetchColumn() > 0;
    }

    private function getCurrentLikeStatus($userID, $postID) {
        $sql = "SELECT LikeStatus FROM Likes WHERE PostID = :PostID AND UserID = :UserID;";

        $bindings = [':UserID' => $userID, ':PostID' => $postID];

        $stmt = $this->executeStatement($sql,$bindings);

        return $stmt !== false && $stmt->fetchColumn();
    }

    private function updateLikeStatus($userID, $postID,$toggleLikeStatus) {
        $sql = "UPDATE Likes SET LikeStatus = :LikeStatus WHERE PostID = :PostID AND UserID = :UserID;";

        $bindings = [':UserID' => $userID, ':PostID' => $postID, ':LikeStatus' => $toggleLikeStatus];

        $stmt = $this->executeStatement($sql,$bindings);

        return $stmt !== false && (bool) $stmt;
    }

    private function insertLike($userID,$postID,$likeStatus) {
        $sql = "INSERT INTO Likes (PostID, UserID, LikeStatus)
                VALUES (:PostID, :UserID, :LikeStatus);";

        $bindings = [':PostID' => $postID, ':UserID' => $userID, ':LikeStatus' => $likeStatus];

        $stmt = $this->executeStatement($sql,$bindings);

        return $stmt !== false && (bool) $stmt;
    }

    private function decrementLikeCount($postID) {
        $sql = "UPDATE likecount SET TotalLikeCount = TotalLikeCount - 1 WHERE PostID = :PostID;";

        $bindings = [':PostID' => $postID];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    private function incrementLikeCount($postID) {
        $sql = "UPDATE likecount SET TotalLikeCount = TotalLikeCount + 1 WHERE PostID = :PostID;";

        $bindings = [':PostID' => $postID];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    public function setLike($userID, $postID) {
        if ($this->isUserAlreadyLike($userID, $postID)) {
            $currentLikeStatus = $this->getCurrentLikeStatus($userID, $postID);
            
            $toggleLikeStatus = ($currentLikeStatus === true) ? false : true;

            if(!$toggleLikeStatus){
                $this->decrementLikeCount($postID);
                return $this->updateLikeStatus($userID, $postID,$toggleLikeStatus);
            }

            $this->incrementLikeCount($postID);
            return $this->updateLikeStatus($userID, $postID,$toggleLikeStatus);
        } else {
            $likeStatus = true;
            return $this->insertLike($userID,$postID,$likeStatus);
        }
    }
}