<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManagePath;
use Functions\ManageResponse;
use viceNet\Profile;

SessionManager::start();
$sessionUser = SessionManager::get('userID');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && !is_null($sessionUser) ) {
    
    $action = $_POST['action'];
    $profile = new Profile();

    switch ($action) {
        case 'profileData':
            sendProfileData( $profile ,$sessionUser);
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }

} else {
    SessionManager::setRedirect('login');
    $response = ['status' => false];
    ManageResponse::sendResponse( $response);
}

function sendProfileData( Profile $profile , $sessionUser) {
    $profileData = $profile->getProfile($sessionUser);

    ManageResponse::sendResponse( $profileData);
}