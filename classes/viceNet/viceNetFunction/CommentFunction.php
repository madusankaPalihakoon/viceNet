<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Functions\ErrorLogger;

class CommentFunction {
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
            ErrorLogger::logError(null, $e, 'comment');
            return false;
        }
    }

    public function setComment($commentData) : bool {
        $sql = "INSERT INTO Comments (PostID, UserID, CommentText)
                VALUES (:PostID , :UserID, :CommentText);";

        $bindings = [':PostID' => $commentData['postID'] , ':UserID' => $commentData['user'], ':CommentText' => $commentData['commentText']];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    public function getComment($postID): ?array {
        $sql = "SELECT
                    C.CommentID,
                    C.CommentText,
                    C.CommentTime,
                    U.username AS UserName,
                    P.PostText
                FROM
                    Comments C
                JOIN
                    Users U ON C.UserID = U.UserID
                JOIN
                    Posts P ON C.PostID = P.PostID
                WHERE
                    C.PostID = :PostID;
                ";
        $stmt = $this->executeStatement($sql, [':PostID' => $postID]);
    
        // Check if the statement was executed successfully
        if ($stmt !== false) {
            // Check if there are any rows returned
            $result = $stmt->fetchAll();
            return (!empty($result)) ? $result : null;
        } else {
            return null; // or handle the error as needed
        }
    }    
}

// SELECT
//     p.PostID,
//     p.UserID AS PostUserID,
//     p.PostText,
//     p.PostImg,
//     p.PostTime,
//     lc.TotalLikeCount,
//     u.Name AS UserName,
//     pr.ProfileImg AS UserProfileImg,
//     l.LikeStatus,
//     c.CommentID,
//     c.UserID AS CommentUserID,
//     cu.Name AS CommentUserName,
//     c.CommentText,
//     cp.ProfileImg AS CommentProfileImg,
//     c.CommentTime
// FROM
//     Post p
// JOIN
//     Users u ON p.UserID = u.UserID
// LEFT JOIN
//     LikeCount lc ON p.PostID = lc.PostID
// LEFT JOIN
//     Likes l ON p.PostID = l.PostID AND l.UserID = '1aa32bb4-11c1-4d98-bf10-623f0557c39b'
// LEFT JOIN
//     Comments c ON p.PostID = c.PostID
// LEFT JOIN
//     Users cu ON c.UserID = cu.UserID
// LEFT JOIN
//     Profile pr ON p.UserID = pr.UserID
// LEFT JOIN
//     Profile cp ON c.UserID = cp.UserID
// WHERE
//     p.UserID = '1aa32bb4-11c1-4d98-bf10-623f0557c39b';
