<?php

use ViceNet\Classes\User;

require_once __DIR__ . "/../functions/profileSetupErrorChecker.php";
require_once __DIR__ . "/../functions/redirectFunction.php";
require_once __DIR__ . '/../config/SessionConfig.php';
require_once __DIR__ . '/../classes/Classes/User.php';

// Constants
define('VALID_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png']);
define('MAX_IMAGE_SIZE', 5242880); // 5MB in bytes

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if ( checkPostArrayAndKeyIsSet() && checkFilesArrayAndKeyIsSet()) {
        $profilePicture = uploadProfilePicture();
        $coverPicture = uploadCoverPicture();
        handleFormData( $profilePicture, $coverPicture);
    }elseif( checkPostArrayAndKeyIsSet() ){
        handleFormData();
    }elseif ( checkFilesArrayAndKeyIsSet() ) {
        $profilePicture = uploadProfilePicture();
        $coverPicture = uploadCoverPicture();
        handleFormData( $profilePicture, $coverPicture);
    }else{
        redirectTo('profile_setup');
    }
}
else
{
    redirectTo('profile_setup');
}

function checkPostArrayAndKeyIsSet() : bool {
    $postKeys = ['home_town', 'contact_info', 'education', 'employment', 'relationship_status', 'hobbies'];

    foreach ($postKeys as $key) {
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            return true;
        }
    }
    return false;
}


function checkFilesArrayAndKeyIsSet() : bool {
    $fileKeys = ['profile_picture', 'cover_picture'];

    foreach ($fileKeys as $key) {
        if (isset($_FILES[$key]) && !empty($_FILES[$key])) {
            return true;
        }
    }
    return false;
}

function IsImageExtensionValid($ImageExtension): bool {
    return in_array($ImageExtension, VALID_IMAGE_EXTENSIONS);
}

function IsImageSizeTooLarge($imageSize): bool {
    return ($imageSize <= MAX_IMAGE_SIZE);
}

function moveUploadFile($tempName, $directoryName, $imgName): bool {
    return move_uploaded_file($tempName, $directoryName . '/' . $imgName);
}

function uploadProfilePicture() : ?string {
    if (isset($_FILES["profile_picture"]["name"]) && $_FILES["profile_picture"]["name"] !== "") {
        $profilePicName = $_FILES["profile_picture"]["name"];
        $profilePicSize = $_FILES["profile_picture"]["size"];
        $profilePicTmpName = $_FILES["profile_picture"]["tmp_name"];
        $profileImageExtension = strtolower(pathinfo($profilePicName, PATHINFO_EXTENSION));
        $profilePicUploadDir = __DIR__ . '/../assets/uploads/profilePic';

        if (!IsImageExtensionValid($profileImageExtension)) {
            redirectTo('profile_setup', ['profile_setup_error' => 'Invalid Image Extension']);
        }

        if (!IsImageSizeTooLarge($profilePicSize)) {
            redirectTo('profile_setup', ['profile_setup_error' => 'Image Size Is Too Large']);
        }

        if (!moveUploadFile($profilePicTmpName, $profilePicUploadDir, $profilePicName)) {
            redirectTo('profile_setup', ['profile_setup_error' => 'Error Upload Images']);
        }

        return $profilePicName;
    }
    else {
        return null;
    }
}

function uploadCoverPicture() : ?string {
    if (isset($_FILES["cover_picture"]["name"]) && $_FILES["cover_picture"]["name"] !== "") {
        $coverPicName = $_FILES["cover_picture"]["name"];
        $coverPicSize = $_FILES["cover_picture"]["size"];
        $coverPicTmpName = $_FILES["cover_picture"]["tmp_name"];
        $coverImageExtension = strtolower(pathinfo($coverPicName, PATHINFO_EXTENSION));
        $coverPicUploadDir = __DIR__ . '/../assets/uploads/coverPic';

        if (!IsImageExtensionValid($coverImageExtension)) {
            redirectTo('profile_setup', ['profile_setup_error' => 'Invalid Image Extension']);
        }

        if (!IsImageSizeTooLarge($coverPicSize)) {
            redirectTo('profile_setup', ['profile_setup_error' => 'Image Size Is Too Large']);
        }

        if (!moveUploadFile($coverPicTmpName, $coverPicUploadDir, $coverPicName)) {
            redirectTo('profile_setup', ['profile_setup_error' => 'Error Upload Images']);
        }
        
        return $coverPicName;
    }
    else {
        return null;
    }
}

function handleFormData( string $profilePic = null, string $coverPic = null) : void {
    $userId = isset($_SESSION['session_id']);
    $homeTown = isset($_POST['home_town']) && $_POST['home_town'] !== '' ? $_POST['home_town'] : null;
    $contactInfo = isset($_POST['contact_info']) && $_POST['contact_info'] !== '' ? $_POST['contact_info'] : null;
    $education = isset($_POST['education']) && $_POST['education'] !== '' ? $_POST['education'] : null;
    $employment = isset($_POST['employment']) && $_POST['employment'] !== '' ? $_POST['employment'] : null;
    $relationshipStatus = isset($_POST['relationship_status']) && $_POST['relationship_status'] !== '' ? $_POST['relationship_status'] : null;
    $hobbies = isset($_POST['hobbies']) && $_POST['hobbies'] !== '' ? $_POST['hobbies'] : null;       
    $user = new User();
    $user->profileSetup($userId, $profilePic, $coverPic, $homeTown, $contactInfo, $education, $employment, $relationshipStatus, $hobbies);
}