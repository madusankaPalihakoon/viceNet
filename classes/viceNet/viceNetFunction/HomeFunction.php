<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;
use LengthException;

SessionManager::start();

class HomeFunction {
    private $con;
    private $pdo;

    public function __construct() {
        $this->con = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $this->pdo = $this->con->connect();
    }

    public function getPost( $userID) : ?array {
        try {
            // Begin transaction
            $this->pdo->beginTransaction();
            $sql = "SELECT
                        p.PostID,
                        p.UserID,
                        p.PostText,
                        p.PostImg,
                        p.PostTime,
                        lc.TotalLikeCount,
                        u.Name,
                        pr.ProfilePic,
                        l.LikeStatus 
                    FROM
                        post p
                    JOIN
                        users u ON p.UserID = u.UserID
                    LEFT JOIN
                        likecount lc ON p.PostID = lc.PostID
                    LEFT JOIN
                        likes  l ON p.PostID = l.PostID AND l.UserID = :UserID
                    LEFT JOIN
                        profile pr ON p.UserID = pr.UserID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':UserID', $userID);
            $stmt->execute();
            // Fetch the result as an associative array
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Commit the transaction if the update is successful
            $this->pdo->commit();

            return $rows;
            
        } catch (\PDOException $e) {
            // Rollback the transaction on any exception
            $this->pdo->rollBack();

            // Log the error with relevant information
            ErrorLogger::logError(null, $e, 'setProfile');

            // Return Null
            return null;
        } finally {
            // Ensure that the transaction is always either committed or rolled back
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
        }
    }

    public function getStory( $userID) : ?array {
        return array();
    }
}