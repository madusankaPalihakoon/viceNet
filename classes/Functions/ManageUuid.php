<?php
namespace Functions;
require_once __DIR__.'/../../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

class ManageUuid{
    public function __construct(){
    }

    public static function generateUUID() : ?string {
        try {
            return $uuid = Uuid::uuid4()->toString();
        } catch (\Throwable $th) {
            ErrorLogger::logError(null, $th, 'uuid');
            return null;
        }
    }    

    public static function validateUUID($uuid) : bool {
        return Uuid::isValid($uuid);
    }    
}