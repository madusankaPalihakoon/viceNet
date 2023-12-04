<?php
namespace ViceNet\Classes;

use Exception;
require_once __DIR__."/../../functions/postErrorChecker.php";
require_once __DIR__."/../../functions/redirectFunction.php";
require_once __DIR__."/../../functions/InputSanitizer.php";

class PostFunction {
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Log the error message to user_signup_log.txt
    private function logFunctionError($e) {
        // Define the error log file path using an absolute path
        $error_log = __DIR__ . '/../../var/log/login.log';
    
        // Include additional information in the log entry (optional)
        $log_entry = '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
        
        // Append a new line to the log entry
        $log_entry .= PHP_EOL;
    
        // Log the error using error_log, which handles logging to the specified file
        error_log($log_entry, 3, $error_log);
    
        // Redirect to the error page
        $error_page = __DIR__.'/../../pages/error.php';
    
        // Ensure the error page exists before redirecting
        if (file_exists($error_page)) {
            header("Location: $error_page");
            exit;
        } else {
            // Handle the case where the error page does not exist
            // You can log this error separately or handle it in another way
            error_log("Error page not found: $error_page");
        }
    }

    public function addPostDetailsToDatabase( int $userId, string $fileName , string $caption) : bool
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO `posts`(`user_id`, `post_img`, `post_caption`) VALUES (:user_id,:post_img,:post_caption)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':post_img', $fileName);
            $stmt->bindParam(':post_caption', $caption);
            return $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
            return false;
        }
    }

    public function getPostDetails(int $requestedBatchIndex): array {
        $requestedRowIndex = 0;
        $responses = array();
        $rowCount = $this->getRowCount();
    
        if ($rowCount === null) {
            return $responses;
        }
    
        $batchSize = 2;
        $numBatches = ceil($rowCount / $batchSize);
    
        for ($i = $requestedBatchIndex; $i < $numBatches; $i++) {
            $offset = $i * $batchSize;
            $rows = $this->fetchRowsInBatch($offset, $batchSize);
    
            $remainingRows = $rowCount - (($i + 1) * $batchSize);

            $responses[] = [
                'status' => 'success' ,
                'remainingRows' => $remainingRows ,
                'data' => $rows ,
            ];

            return $responses;

            if ($i === $requestedBatchIndex) {
                break;
            }
        }
    
        // If the requested batch index is valid, return the corresponding row(s)
        if ($requestedBatchIndex < $numBatches) {
            $requestedRows = $responses[$requestedBatchIndex]['data'];
            return array_slice($requestedRows, $requestedRowIndex, 2);
        }
    
        return $responses;
    }

    private function getRowCount(): ?int {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            $stmt->closeCursor();
    
            return ($count !== false && $count !== null) ? (int)$count : null;
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
            return null;
        }
    }

    private function fetchRowsInBatch(int $offset, int $batchSize): array {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM posts LIMIT :offset, :batchSize");
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $stmt->bindParam(':batchSize', $batchSize, \PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the rows
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $rows;
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
            return [];
        }
    }
    
    public function handleLike($current_user, $postId, $like_status) {
        try {
            $this->pdo->beginTransaction();
            $current_status = $this->checkCurrentLikeStatus($current_user, $postId);
            switch ($current_status) {
                case '0':
                    // Current like status is dislike
                    $like_status = 1;
                    $this->updateLike($current_user, $postId, $like_status);
                    $this->incrementLikeCounter($postId);
                    break;
        
                case '1':
                    // Current like status is like
                    $like_status = 0;
                    $this->updateLike($current_user, $postId, $like_status);
                    $this->decrementLikeCounter($postId);
                    break;
                
                default:
                    // User has not liked or disliked
                    $this->addLike($current_user, $postId, $like_status);
                    $like_count =1;
                    $this->addLikeCounter($postId,$like_count);
                    break;
            }
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->logFunctionError($e);
        }
    }

    private function checkCurrentLikeStatus($current_user, $post_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT pl.like_status FROM posts p LEFT JOIN post_like pl ON p.post_id = pl.post_id AND pl.liked_user = :liked_user  WHERE p.post_id = :post_id;");
            $stmt->bindParam(':liked_user', $current_user, \PDO::PARAM_INT);
            $stmt->bindParam(':post_id', $post_id, \PDO::PARAM_INT);
            $stmt->execute();
    
            // Fetch the result as an associative array
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            // Return the result value directly
            return $result['like_status'];
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }
    
    private function updateLike($current_user, $postId, $like_status): bool {
        try {
            $stmt = $this->pdo->prepare("UPDATE post_like SET like_status = :like_status WHERE post_id = :post_id AND liked_user = :liked_user;");
            $stmt->bindParam(':post_id', $postId, \PDO::PARAM_INT);
            $stmt->bindParam(':liked_user', $current_user, \PDO::PARAM_INT);
            $stmt->bindParam(':like_status', $like_status, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }
    
    private function addLike($current_user, $postId, $like_status): bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO post_like (post_id, liked_user, like_status) VALUES (:post_id, :liked_user, :like_status);");
            $stmt->bindParam(':post_id', $postId, \PDO::PARAM_INT);
            $stmt->bindParam(':liked_user', $current_user, \PDO::PARAM_INT);
            $stmt->bindParam(':like_status', $like_status, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }

    private function addLikeCounter($postId, $like_count) : void {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO `like_count`(`post_id`, `like_count`) VALUES (:post_id,:like_count)");
            $stmt->bindParam(':post_id', $postId, \PDO::PARAM_INT);
            $stmt->bindParam(':like_count', $like_count, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }

    private function incrementLikeCounter($postId) : void {
        try {
            $stmt = $this->pdo->prepare("UPDATE like_count SET like_count = like_count + 1 WHERE post_id = :post_id;");
            $stmt->bindParam(':post_id' , $postId, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }

    private function decrementLikeCounter ($postId) : void {
        try {
            $stmt = $this->pdo->prepare("UPDATE like_count SET like_count = like_count - 1 WHERE post_id = :post_id;");
            $stmt->bindParam(':post_id' , $postId, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }

    public function addComment($userId, $postId, $commentText) : array {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO `post_comment`(`post_id`, `comment_user`, `comment_text`) VALUES (:post_id,:comment_user,:comment_text);");
            $stmt->bindParam(':post_id' , $postId, \PDO::PARAM_INT);
            $stmt->bindParam(':comment_user' , $userId, \PDO::PARAM_INT);
            $stmt->bindParam(':comment_text' , $commentText, \PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $responses = [
                    'status' => 'success' 
                ];
            } else {
                return $responses = [
                    'status' => 'fail'
                ];
            }
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }

    public function getCommentFromDatabase( $postId) : ?array {
        try {
            $stmt = $this->pdo->prepare("SELECT u.name AS userName, pc.comment_text , pc.timestap, pc.post_id FROM post_comment pc JOIN users u ON pc.comment_user = u.user_id WHERE post_id = :post_id;");
            $stmt->bindParam(':post_id' , $postId);
            $stmt->execute();
            // Fetch all rows as associative arrays
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Return the fetched rows or null if there are no comments
            return (count($rows) > 0) ? $rows : null;
        } catch (\PDOException $e) {
            $this->logFunctionError($e);
        }
    }
}