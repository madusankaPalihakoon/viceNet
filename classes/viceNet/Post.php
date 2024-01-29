<?php

namespace viceNet;

use viceNetFunction\PostFunction;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

class Post{
    private $postFunction;

    public function __construct() {
        $this->postFunction = new PostFunction();
    }

    public function setPost( array $postData) : bool {
        return $this->postFunction->setPost( $postData);
    }

    public function getPost($user) {
        return $this->postFunction->getPost($user);
    }

    public function getUsersPost($user) {
        return $this->postFunction->getUsersPost($user);
    }
}