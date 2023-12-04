<?php
namespace ViceNet\Classes;
require_once __DIR__."/../Database/databaseCon.php";
require_once __DIR__."/Post_function.php";
use ViceNet\Database\PDO_Config;

class Post {
    private $postFunction;

    public function __construct()
    {
        $database = new PDO_Config();
        $pdo = $database->getConnection(); // Get the PDO instance
        $this->postFunction = new PostFunction($pdo);
    }

    public function setPost(int $userId, string $fileName, string $caption) : bool {
        return $this->postFunction->addPostDetailsToDatabase( $userId, $fileName , $caption);
    }

    public function getPost($batchIndex) : ?array {
        return $this->postFunction->getPostDetails( $batchIndex);
    }

    public function setLike($current_user,$postId,$Status) {
        $this->postFunction->handleLike($current_user,$postId,$Status);
    }
}