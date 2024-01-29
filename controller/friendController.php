<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ErrorLogger;
use Functions\ManageResponse;
use Functions\ManageUuid;
use Functions\Validator;
use viceNet\Friend;

SessionManager::start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
    $action = $_POST['action'];
    $profileID = $_POST['id'];
    $request = new Friend();

    switch ($action) {
        case 'sendRequest':
            handleSendRequest($request, $profileID);
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }

    echo json_encode(['action' => $_POST['id']]);
}else{
    ManageResponse::handleInvalidRequest();
}

function handleSendRequest(Friend $request, $profileID){
    $request->sendRequest( $profileID);
}