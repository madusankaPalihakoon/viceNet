<?php

namespace viceNet;

use viceNetFunction\StoryFunction;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../config/db.php';

class Story{
    private $storyFunction;

    public function __construct() {
        $this->storyFunction = new StoryFunction();
    }

    public function setStory( $storyData) : bool {
        return $this->storyFunction->setStory( $storyData);
    }

    public function getStory( $userID) : array {
        return $this->storyFunction->getStory( $userID);
    }
}