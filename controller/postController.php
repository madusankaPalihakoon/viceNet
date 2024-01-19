<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManageResponse;
use Functions\ManageUuid;
use Functions\Validator;
use viceNet\Comment;
use viceNet\Like;

SessionManager::start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'updateLike':
            handleUpdateLike();
            break;
        
        case 'submitComment':
            handleSubmitComment();
            break;

        default:
            ManageResponse::handleInvalidAction();
            break;
    }
} else {
    ManageResponse::handleInvalidRequest();
}

function handleUpdateLike() {
    if(!isset($_POST['postID'])) {
        ManageResponse::handleInvalidAction();
    }
    $postID = $_POST['postID'];

    if(!ManageUuid::validateUUID($postID)){
        ManageResponse::handleInvalidAction();
    }

    $user = SessionManager::getSessionUser();

    $like = new Like();
    
    $result = $like->setLike($user,$postID);

    // ManageResponse::sendResponse(['status' => $result]);
    ManageResponse::sendResponse(['result' => $result]);
}

function handleSubmitComment() {
    if(empty($_POST['comment-text'])) {
        ManageResponse::sendResponse(['status' => false]);
    }

    $postID = $_POST['postId'];
    $commentText = Validator::validateInput($_POST['comment-text']);
    $user = SessionManager::getSessionUser();

    $comment = new Comment();

    $commentData = compact('postID','commentText','user');

    $comment->setComment($commentData);

    ManageResponse::sendResponse(['status' => true]);
}