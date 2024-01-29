<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManageResponse;
use viceNet\Friend;
use viceNet\Post;
use viceNet\Story;

SessionManager::start();
$sessionUser = SessionManager::get('userID');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && !is_null($sessionUser) ) {
    $action = $_POST['action'];

    switch ($action) {
        case 'getFriend':
            handleSendFriendData();
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }
} else {
    ManageResponse::handleInvalidRequest();
}

function handleSendFriendData() {
    $friend = new Friend();
    ManageResponse::sendResponse($friend->getFriend());
}