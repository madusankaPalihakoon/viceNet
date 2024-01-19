<?php
require_once __DIR__.'/../vendor/autoload.php';
use Configuration\SessionManager;
use Functions\ManageResponse;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'getMessage':
            SessionManager::start();
            $message = SessionManager::displayMessage();
            ManageResponse::sendResponse(['message' => $message]);
            break;

        default:
            ManageResponse::handleInvalidAction();
            break;
    }
} else {
    echo json_encode(['status' => false , 'error' => 'invalid request method']);
}
