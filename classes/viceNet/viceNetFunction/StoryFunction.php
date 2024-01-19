<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;
use Ramsey\Uuid\Uuid;

SessionManager::start();

class StoryFunction {
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

    public function setStory($storyData) :bool {
        $sql = "INSERT INTO `story`(`StoryID`, `UserID`, `StoryImg`, `StoryText`) VALUES (:StoryID, :UserID, :StoryImg, :StoryText)";

        $bindings = [
            ':StoryID' => $storyData['StoryID'], 
            ':UserID' => $storyData['UserID'], 
            ':StoryImg' => $storyData['StoryImg'], 
            ':StoryText' => $storyData['StoryText'],
        ];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    public function getStory($userID) :array {
        $sql = "SELECT `StoryImg`, `StoryText` FROM `story` WHERE UserID = :UserID";

        $stmt = $this->executeStatement($sql,[':UserID' => $userID]);

        if($stmt !== false) {
            $key = 'storyImgPath';
            $path = "../uploads/$userID/story";

            $storyData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($storyData as &$Data) {
                $Data[$key] = $path;
            }
            unset($Data);

            return $storyData;

        } else {
            return [];
        }
    }
}