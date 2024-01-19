<?php
require_once __DIR__.'/../vendor/autoload.php';
use Configuration\SessionManager;
use Functions\ManageResponse;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] ) {
    $action = $_POST['action'];

    switch ($action) {
        case 'getRedirect':
            SessionManager::start();
            $url = SessionManager::sendRedirectPage();
            ManageResponse::sendResponse(['url' => $url]);
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }
} else {
    ManageResponse::handleInvalidRequest();
}
