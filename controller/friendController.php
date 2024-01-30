<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManageResponse;
use Functions\ManageUuid;
use viceNet\Friend;

SessionManager::start();
$sessionUser = SessionManager::get('userID');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['id']) && isset($sessionUser) ) {
    $action = $_POST['action'];
    $requestUser = $_POST['id'];
    $request = new Friend();

    switch ($action) {
        case 'sendRequest':
            handleSendRequest($request, $requestUser, $sessionUser);
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }
}else{
    ManageResponse::handleInvalidRequest();
}

function handleSendRequest(Friend $request, $requestUser, $sessionUser){
    if(ManageUuid::validateUUID($requestUser) && ManageUuid::validateUUID($sessionUser)){
        // if(!$request->sendRequest( $requestUser, $sessionUser)){
        //     ManageResponse::sendResponse(['status' => false]);
        // }

        // ManageResponse::sendResponse(['status' => true]);
        $result = $request->sendRequest( $requestUser, $sessionUser);
        ManageResponse::sendResponse(['status' => $result]);
    } else {
        ManageResponse::sendResponse(['status' => false]);
    }   
}
