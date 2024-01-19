<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManagePath;
use Functions\ManageResponse;
use Functions\ManageUuid;
use Functions\Validator;
use viceNet\Post;
use viceNet\Story;

SessionManager::start();
const VALID_IMG_TYPE = ['image/jpg' , 'image/jpeg' , 'image/png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'uploadPost':
            handlePostUpload();
            break;
        
        case 'uploadStory':
            handleStoryUpload();
            break;

        default:
            ManageResponse::handleInvalidAction();
            break;
    }
} else {
    ManageResponse::handleInvalidRequest();
}

function handlePostUpload() {
    $postImg = validateImgAndUpload('postImg');

    if(is_null($postImg)) {
        SessionManager::setMessage('Something went wrong. please try again later!');
        $response = ['status' => false];
        ManageResponse::sendResponse($response);
    }

    if(isset($_POST['postText'])) {
        $postText = Validator::validateInput($_POST['postText']);
    } else {
        $postText = "";
    }

    $PostID = ManageUuid::generateUUID();

    $userID = SessionManager::getSessionUser();

    $postData = [
        'PostID' => $PostID,
        'UserID' => $userID,
        'PostImg' => $postImg,
        'PostText' => $postText
    ];

    $post = new Post();

    $post->setPost( $postData);

    ManageResponse::sendResponse(['status' => true]);
}

function handleStoryUpload() {
    $storyImg = validateImgAndUpload('storyImg');

    if(is_null($storyImg)) {
        SessionManager::setMessage('Something went wrong. please try again later!');
        $response = ['status' => false];
        ManageResponse::sendResponse($response);
    }

    if(isset($_POST['storyText'])) {
        $storyText = Validator::validateInput($_POST['storyText']);
    } else {
        $storyText = "";
    }

    $StoryID = ManageUuid::generateUUID();

    $userID = SessionManager::getSessionUser();

    $storyData = [
        'StoryID' => $StoryID,
        'UserID' => $userID,
        'StoryImg' => $storyImg,
        'StoryText' => $storyText
    ];

    $story = new Story();

    $story->setStory($storyData);

    ManageResponse::sendResponse(['status' => true]);
}

function validateImgAndUpload($name) {
    if(!in_array($_FILES[$name]['type'], VALID_IMG_TYPE )){
        if ($name === 'postImg'){
            $message = 'Your post image is invalid image Type!';
        } else {
            $message = 'Your Story image invalid image Type!';
        }
        $response = [
            'status' => false,
            'error' => $message,
        ];
        SessionManager::setMessage($message);
        ManageResponse::sendResponse($response);
    }

    if (!$_FILES[$name]['error'] === UPLOAD_ERR_OK){
        if ($name === 'postImg'){
            $message = 'Error upload your Post.Please Select another image!';
        } else {
            $message = 'Error upload your Story.Please Select another image!';
        }
        $response = [
            'status' => false,
            'error' => $message
        ];
        SessionManager::setMessage($message);
        ManageResponse::sendResponse($response);
    }

    return uploadImg($name);
}

function uploadImg($name) {
    $tempName = $_FILES[$name]['tmp_name'];
    $originalImgName = basename($_FILES[$name]['name']);

    // generate unique ID for img name
    $imgID = uniqid();
    $number = rand(1000,9999);

    // Extract file extension
    $imgExtension = pathinfo($originalImgName, PATHINFO_EXTENSION);

    $imgName = $imgID . $number . '.' . $imgExtension;

    if ($name === 'postImg') {
        $uploadDir = ManagePath::getPostUploadDirectory();
    } else {
        $uploadDir = ManagePath::getStoryUploadDirectory();
    }

    if (is_null($uploadDir)) {
        return null;
    }

    $uploadPath = $uploadDir . '/' . $imgName;
    move_uploaded_file($tempName, $uploadPath);
    return $imgName;
}