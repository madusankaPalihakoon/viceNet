<?php
namespace viceNet;

require_once __DIR__.'/../../vendor/autoload.php';

use viceNetFunction\ProfileFunction;

class Profile {
    private $profileFunction;

    public function __construct() {
        $this->profileFunction = new ProfileFunction();
    }
    // Create new profile
    public function setProfile( array $profileData, $userID) :bool {
        return $this->profileFunction->setProfile( $profileData, $userID);
    }

    public function getProfile( $user) {
        return $this->profileFunction->getProfile( $user);
    }
}