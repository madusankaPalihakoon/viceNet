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
        case 'Send Request':
            handleSendRequest($request, $requestUser, $sessionUser);
            break;

        case 'Response':
            // ManageResponse::sendResponse(['action' => $action]);
            handleResponseRequest($request, $requestUser, $sessionUser);
            break;

        case 'Pending':
            # code...
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
        $result = $request->sendRequest( $requestUser, $sessionUser);
        ManageResponse::sendResponse(['status' => $result]);
    } else {
        ManageResponse::sendResponse(['status' => false]);
    }   
}

function handleResponseRequest(Friend $request, $sender, $receiver) {
    if(ManageUuid::validateUUID($sender) && ManageUuid::validateUUID($receiver)) {
        $result = $request->responseRequest($sender, $receiver);
        ManageResponse::sendResponse(['status' => $result]);
    }
}
