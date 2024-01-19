<?php

namespace viceNet;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

use viceNetFunction\CommentFunction;

class Comment {
    private $commentFunction;

    public function __construct() {
        $this->commentFunction = new CommentFunction();
    }

    public function setComment($commentData) : bool {
        return $this->commentFunction->setComment($commentData);
    }

    public function getComment( $postID) : array {
        return $this->commentFunction->getComment( $postID);
    }
}