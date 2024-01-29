<?php

namespace viceNet;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

use viceNetFunction\FriendFunction;
class Friend {
    private $friendFunction;

    public function __construct() {
        $this->friendFunction = new FriendFunction();
    }

    public function getFriend($sessionUser){
        return $this->friendFunction->getFriend($sessionUser);
    }

    public function sendRequest( $profileID){
        return $this->friendFunction->sendRequest( $profileID);
    }
}