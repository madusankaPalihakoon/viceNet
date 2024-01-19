<?php

namespace viceNet;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

use viceNetFunction\HomeFunction;

class Home {
    private $homeFunction;

    public function __construct() {
        $this->homeFunction = new HomeFunction();
    }

    public function getPost( $userID) : ?array {
        return $this->homeFunction->getPost( $userID);
    }

    public function getStory( $userID) : ?array {
        return $this->homeFunction->getStory( $userID);
    }

}