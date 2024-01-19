<?php
namespace Functions;

use Configuration\SessionManager;
SessionManager::start();

require_once __DIR__.'/../../vendor/autoload.php';

class ManagePath {
    public function __construct(){
    }

    private static function getUserId() {
        try {
            return SessionManager::get('userID');
        } catch (\Exception $e) {
            ErrorLogger::logError($e,null,'path');
            return null;
        }
    }

    private static function getUploadPath() {
        try {
            $uploadPath = __DIR__.'/../../uploads/';
            $usersFolderPath = $uploadPath . self::getUserId();

            // check folder path already exists
            if (!is_dir($usersFolderPath)) {
                // Create folder path if it doesn't exist
                if (mkdir($usersFolderPath, 0755, true)) {
                    return $usersFolderPath;
                } else {
                    throw new \Exception('Failed to create user folder path.');
                }
            } else {
                return $usersFolderPath;
            }
        } catch (\Exception $e) {
            ErrorLogger::logError($e,null,'path');
            return null;
        }
    }

    private static function getSpecificUploadDirectory($directory) {
        try {
            // get users upload path
            $usersFolderPath = self::getUploadPath();
    
            if ($usersFolderPath !== null) {
                // add directory name to folder path
                $specificPath = $usersFolderPath . '/' . $directory;
    
                // Check if the upload path doesn't exist
                if (!is_dir($specificPath)) {
                    // make upload path
                    if (mkdir($specificPath, 0755)) {
                        return $specificPath;
                    } else {
                        throw new \Exception('Failed to create '. $directory .' folder path for user '. self::getUserId());
                    }
                } else {
                    return $specificPath;
                }
            }
        } catch (\Exception $e) {
            ErrorLogger::logError($e,null,'path');
            return null;
        }
    }    

    public static function getProfileUploadDirectory() {
        return self::getSpecificUploadDirectory('profile');
    }

    public static function getCoverUploadDirectory() {
        return self::getSpecificUploadDirectory('cover');
    }

    public static function getPostUploadDirectory() {
        return self::getSpecificUploadDirectory('post');
    }

    public static function getStoryUploadDirectory() {
        return self::getSpecificUploadDirectory('story');
    }
}