<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
use Functions\ManagePath;
use Functions\ManageResponse;
use Functions\Validator;
use viceNet\Profile;

SessionManager::start();
const VALID_IMG_TYPE = ['image/jpg' , 'image/jpeg' , 'image/png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $userID = SessionManager::get('userID');
    ManageResponse::handleSessionTimeOut( $userID);

    switch ($action) {
        case 'setup':
            handleSetup($userID);
            break;
        
        default:
            ManageResponse::handleInvalidAction();
            break;
    }

} else {
    ManageResponse::handleInvalidRequest();
}

function isRequestFieldAreEmpty() {
    if ( !isset($_FILES['ProfileImg']) || !isset($_FILES['CoverImg']) || empty($_POST['Home']) || empty($_POST['Birthday']) ) {
        $response = [
            'status' => false ,
            'error' => 'Require Fields are Empty. Please fill Require Fields'
        ];
        SessionManager::setMessage("Require Fields are Empty. Please fill Require Fields");
        ManageResponse::sendResponse($response);
    }

    return false;
}

function handleSetup( $userID) {
    if (!isRequestFieldAreEmpty()) {
        $ProfileImg = handleUpload('ProfileImg');
        $CoverImg = handleUpload('CoverImg');
        $Home = Validator::validateInput($_POST['Home']);
        $Birthday = Validator::validateInput($_POST['Birthday']);
        if (empty($_POST['RelationshipStatus'])){
            $RelationshipStatus = null;
        } else {
            $RelationshipStatus = $_POST['RelationshipStatus'];
        }
        if (empty($_POST['Mobile'])) {
            $Mobile = null;
        } else {
            $Mobile = $_POST['Mobile'];
        }
        $ProfileStatus = 1;
    
        $setupData = compact('ProfileImg', 'CoverImg', 'Home', 'Birthday', 'RelationshipStatus', 'Mobile', 'ProfileStatus');
        $profile = new Profile();
        if (!$profile->setProfile( $setupData, $userID)) {
            $response = ['status' => false , 'error' => 'Unable to Setup your profile. Please try again.'];
            SessionManager::setMessage('Unable to Setup your profile. Please try again.');
            ManageResponse::sendResponse( $response);
        }

        $response = ['status' => true];
        SessionManager::setRedirect('profile');
        ManageResponse::sendResponse($response);
    }
}

function handleUpload($name) {
    $imgName = validateImgAndUpload($name);

    if (is_null($imgName)) {
        $response = [
            'status' => false , 
            'error' => "Error: Unable to upload image. Please check the file format and size, and try again.",
        ];
        SessionManager::setMessage("Error: Unable to upload image. Please check the file format and size, and try again.");
        ManageResponse::sendResponse($response);
    }

    return $imgName;
}

function validateImgAndUpload($name) {
    if(!in_array($_FILES[$name]['type'], VALID_IMG_TYPE )){
        if ($name === 'ProfileImg'){
            $message = 'Your Profile pic invalid Img Type!';
        } else {
            $message = 'Your Cover pic invalid Img Type!';
        }
        $response = [
            'status' => false,
            'error' => $message,
        ];
        SessionManager::setMessage($message);
        ManageResponse::sendResponse($response);
    }

    if (!$_FILES[$name]['error'] === UPLOAD_ERR_OK){
        if ($name === 'ProfileImg'){
            $message = 'Error upload your Profile Pic.Please Select another picture!';
        } else {
            $message = 'Error upload your Cover Pic.Please Select another picture!';
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

    if ($name === 'ProfileImg') {
        $uploadDir = ManagePath::getProfileUploadDirectory();
    } else {
        $uploadDir = ManagePath::getCoverUploadDirectory();
    }

    if (is_null($uploadDir)) {
        return null;
    }

    $uploadPath = $uploadDir . '/' . $imgName;
    move_uploaded_file($tempName, $uploadPath);
    return $imgName;
}