<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;
use Ramsey\Uuid\Uuid;

SessionManager::start();

class PostFunction {
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
            ErrorLogger::logError(null, $e, 'post');
            return false;
        }
    }

    public function setPost($postData): bool {
        $sql = "INSERT INTO `post`(`PostID`, `UserID`, `PostText`, `PostImg`) VALUES (:PostID, :UserID, :PostText, :PostImg)";

        $bindings = [
            ':PostID' => $postData['PostID'], 
            ':UserID' => $postData['UserID'], 
            ':PostText' => $postData['PostText'], 
            ':PostImg' => $postData['PostImg']
        ];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    public function getPost() :array {
        $sql = "SELECT
                    p.PostID,
                    p.UserID,
                    p.PostText,
                    p.PostImg,
                    p.PostTime,
                    lc.TotalLikeCount,
                    u.Name,
                    pr.ProfileImg,
                    l.LikeStatus 
                FROM
                    post p
                JOIN
                    users u ON p.UserID = u.UserID
                LEFT JOIN
                    likecount lc ON p.PostID = lc.PostID
                LEFT JOIN
                    likes  l ON p.PostID = l.PostID
                LEFT JOIN
                    profile pr ON p.UserID = pr.UserID";
    
        $stmt = $this->executeStatement($sql);
    
        if ($stmt !== false) {
            $postData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            foreach ($postData as &$post) {
                $userPath = $post['UserID'];
                
                $key = 'postImgPath';
                $path = "../uploads/$userPath/post";
    
                $key1 = 'profilePath';
                $path1 = "../uploads/$userPath/profile";
    
                $post[$key] = $path;
                $post[$key1] = $path1;
            }
            unset($post);
    
            return $postData;
        } else {
            return [];
        }
    }
    

    public function getUsersPost($user) {
        $sql = "SELECT
                    p.PostID,
                    p.UserID,
                    p.PostText,
                    p.PostImg,
                    p.PostTime,
                    lc.TotalLikeCount,
                    u.Name,
                    pr.ProfileImg,
                    l.LikeStatus
                FROM
                    Post p
                JOIN
                    Users u ON p.UserID = u.UserID
                LEFT JOIN
                    LikeCount lc ON p.PostID = lc.PostID
                LEFT JOIN
                    Likes l ON p.PostID = l.PostID AND l.UserID = :UserID
                LEFT JOIN
                    Profile pr ON p.UserID = pr.UserID
                WHERE
                    p.UserID = :UserID;";
            $bindings = [':UserID' => $user];

            $stmt = $this->executeStatement($sql,$bindings);

            if ($stmt !== false) {
            $key = 'postImgPath';
            $path = "../uploads/$user/post";

            $key1 = 'profilePath';
            $path1 = "../uploads/$user/profile";

            $postData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($postData as &$Data) {
                $Data[$key] = $path;
                $Data[$key1] = $path1;
            }
            unset($Data);

            return $postData;
            } else {
            return [];
            }
    }
}