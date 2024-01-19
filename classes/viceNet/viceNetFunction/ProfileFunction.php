<?php
namespace viceNetFunction;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../config/db.php';

use Configuration\Database;
use Configuration\SessionManager;
use Functions\ErrorLogger;

SessionManager::start();

class ProfileFunction {
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
            ErrorLogger::logError($sql, $e, 'profile');
            return false;
        }
    }

    private function generateSetupSql( $profileData, $userID) {
        $setValues = [];

        $bindingsValues = [];

        foreach ($profileData as $key => $value) {
            if($value !== null) {
                $setValues[] = "`$key` = :$key";
                $bindingsValues[":$key"] = $value;
            }
        }

        $whereStmt = " WHERE UserID = :UserID;";

        $sql = "UPDATE `profile` SET " . implode(", ", $setValues) . $whereStmt;

        $bindingsValues[':UserID'] = $userID;

        return [
            'sql' => $sql,
            'bindings' => $bindingsValues,
        ];

    }

    public function setProfile( $profileData, $userID) :bool {
        $stmtData = $this->generateSetupSql( $profileData, $userID);

        $sql = $stmtData['sql'];
        $bindings = $stmtData['bindings'];

        return (bool) $this->executeStatement($sql,$bindings);
    }

    public function getProfile( $user) {
        $ProfileData = $this->getProfileData( $user);

        $ImgThumbnail = $this->getThumbnailImg( $user);

        return array_merge($ProfileData, $ImgThumbnail);
    }

    private function getProfileData( $user) {
        $sql = "SELECT u.Name, u.country, p.Home, p.Birthday, p.RelationshipStatus, p.Mobile , p.ProfileImg, p.CoverImg
                FROM users u JOIN profile p ON u.UserID = p.UserID
                WHERE u.UserID = :UserID";

        $bindings = [':UserID' => $user];

        $stmt = $this->executeStatement($sql, $bindings);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($rows !== false) {
            $key1 = "ProfileImgPath";
            $path1 = "../uploads/$user/profile";
        
            $key2 = "CoverImgPath";
            $path2 = "../uploads/$user/cover";
        
            foreach ($rows as &$row) {
                $row[$key1] = $path1;
                $row[$key2] = $path2;
            }
            unset($row); // unset the reference to the last element to avoid issues later
        
            return $rows;
        }

        return [];
    }

    public function getThumbnailImg( $user) {
        $sql = "SELECT Img FROM images WHERE UserID = :UserID LIMIT 4;";

        $bindings = [':UserID' => $user];

        $stmt = $this->executeStatement($sql,$bindings);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ( $result !== false && !empty($result) ) {
            // add img path details to result
            return $this->addImgPathDetails( $result, $user);
        }

        return [];

    }

    private function addImgPathDetails($result, $user) {
        $folderPaths = [
            "../uploads/$user/profile",
            "../uploads/$user/cover",
            "../uploads/$user/post",
        ];

        $data = [];

        foreach ($result as $imgName) {
            $imgFileName = $imgName['Img'];
            $foundFile = false;

            foreach ($folderPaths as $folderPath) {
                $imgPath = $folderPath . '/' . $imgFileName;

                if(file_exists($imgPath)) {
                    $data[] = array(
                        'imgPath' => stripslashes($folderPath),
                        'imgName' => $imgFileName,
                    );
                    $foundFile = true;
                }
            }

            if(!$foundFile) {
                $data[] = array(
                    'imgPath' => null,
                    'imgName' => $imgFileName,
                );
            }

        }

        return $data;

    }
}