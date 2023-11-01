<?php

use ViceNet\Classes\User;

require_once __DIR__ . "/../functions/profileSetupErrorChecker.php";
require_once __DIR__ . "/../functions/redirectFunction.php";
require_once __DIR__ . '/../config/SessionConfig.php';
require_once __DIR__ . '/../classes/Classes/User.php';

// Constants
define('VALID_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png']);
define('MAX_IMAGE_SIZE', 1000000);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES) && !empty($_POST)) {
        if (isset($_FILES["profile_picture"]) && isset($_FILES["cover_picture"])) {
            handleUploadData();
            exit;
        } elseif (isset($_FILES["profile_picture"])) {
            handleProfilePic();
            exit;
        } elseif (isset($_FILES["cover_picture"])) {
            handleCoverPic();
            exit;
        }
    } elseif (!empty($_FILES)) {
        if (isset($_FILES["profile_picture"]) && isset($_FILES["cover_picture"])) {
            handleUploadData();
            exit;
        } elseif (isset($_FILES["profile_picture"])) {
            handleProfilePic();
            exit;
        } elseif (isset($_FILES["cover_picture"])) {
            handleCoverPic();
            exit;
        }
    } elseif (!empty($_POST)) {
        uploadBioInformation();
        exit;
    } else {
        redirectTo('profile_setup', ['profile_setup_error' => 'An error occurred. Please try again later']);
        exit;
    }
}

function handleUploadData(): void {
    $profilePicName = $_FILES["profile_picture"]["name"];
    $profilePicSize = $_FILES["profile_picture"]["size"];
    $profilePicTmpName = $_FILES["profile_picture"]["tmp_name"];
    $profileImageExtension = strtolower(pathinfo($profilePicName, PATHINFO_EXTENSION));
    $profilePicUploadDir = __DIR__ . '/../assets/uploads/profilePic';

    $coverPicName = $_FILES["cover_picture"]["name"];
    $coverPicSize = $_FILES["cover_picture"]["size"];
    $coverPicTmpName = $_FILES["cover_picture"]["tmp_name"];
    $coverImageExtension = strtolower(pathinfo($coverPicName, PATHINFO_EXTENSION));
    $coverPicUploadDir = __DIR__ . '/../assets/uploads/coverPic';

    if (!IsImageExtensionValid($profileImageExtension) || !IsImageExtensionValid($coverImageExtension)) {
        redirectTo('profile_setup', ['profile_setup_error' => 'Invalid Image Extension']);
    }

    if (!IsImageSizeTooLarge($profilePicSize) || !IsImageSizeTooLarge($coverPicSize)) {
        redirectTo('profile_setup', ['profile_setup_error' => 'Image Size Is Too Large']);
    }

    if (!moveUploadFile($profilePicTmpName, $profilePicUploadDir, $profilePicName)) {
        redirectTo('profile_setup', ['profile_setup_error' => 'Error Upload Images']);
    }
    if (!moveUploadFile($coverPicTmpName, $coverPicUploadDir, $coverPicName)) {
        redirectTo('profile_setup', ['profile_setup_error' => 'Error Upload Images']);
    }
    uploadBioInformation($profilePicName, $coverPicName);
}

function handleProfilePic(): void {
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
    uploadBioInformation($profilePicName, null);
}

function handleCoverPic(): void {
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
    uploadBioInformation(null, $coverPicName);
}

function IsImageExtensionValid($Image): bool {
    return in_array($Image, VALID_IMAGE_EXTENSIONS);
}

function IsImageSizeTooLarge($imageSize): bool {
    return ($imageSize <= MAX_IMAGE_SIZE);
}

function moveUploadFile($tempName, $directoryName, $imgName): bool {
    return move_uploaded_file($tempName, $directoryName . '/' . $imgName);
}

function uploadBioInformation($profilePic = null, $coverPic = null): void {
    $userId = isset($_SESSION['session_id']) ? $_SESSION['session_id'] : null;
    $homeTown = isset($_POST['home_town']) ? $_POST['home_town'] : null;
    $contactInfo = isset($_POST['contact_info']) ? $_POST['contact_info'] : null;
    $education = isset($_POST['education']) ? $_POST['education'] : null;
    $employment = isset($_POST['employment']) ? $_POST['employment'] : null;
    $relationshipStatus = isset($_POST['relationship_status']) ? $_POST['relationship_status'] : null;
    $hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : null;
    $user = new User();
    $user->profileSetup($userId, $profilePic, $coverPic, $homeTown, $contactInfo, $education, $employment, $relationshipStatus, $hobbies);
}