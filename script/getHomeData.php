<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManageResponse;
use viceNet\Post;
use viceNet\Story;

SessionManager::start();
$sessionUser = SessionManager::get('userID');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && !is_null($sessionUser) ) {
    
    $action = $_POST['action'];
    $story = new Story();
    $post = new Post();

    switch ($action) {
        case 'getStory':
            sendStoryData($story, $sessionUser);
            break;

        case 'getPost':
            sendPostData($post, $sessionUser);
            break;

        case 'userPost':
            sendUserPostData($post, $sessionUser);
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

function sendStoryData(Story $story, $sessionUser) {
    $storyData = $story->getStory( $sessionUser);
    ManageResponse::sendResponse($storyData);
}

function sendPostData(Post $post, $sessionUser) {
    $postData = $post->getPost( $sessionUser);
    ManageResponse::sendResponse($postData);
}

function sendCommentData() {
    
}

function sendUserPostData($post, $sessionUser) {
    $postData = $post->getUsersPost($sessionUser);
    ManageResponse::sendResponse($postData);
}