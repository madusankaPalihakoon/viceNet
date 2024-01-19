<?php

namespace viceNet;

use viceNetFunction\LikeFunction;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

class Like{
    private $likeFunction;

    public function __construct() {
        $this->likeFunction = new LikeFunction();
    }

    public function setLike($userID, $postID) {
        return $this->likeFunction->setLike($userID, $postID);
    }
}