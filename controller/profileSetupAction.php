<?php
require_once __DIR__."/../../functions/profileSetupErrorChecker.php";
require_once __DIR__."/../../functions/redirectFunction.php";
require_once __DIR__.'/../config/SessionConfig.php';
require_once __DIR__.'/../classes/Classes/User.php';
use ViceNet\Classes\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form data here
    $home_town = $_POST['home_town'];
    $contact_info = $_POST['contact_info'];
    $education = $_POST['education'];
    $employment = $_POST['employment'];
    $relationship_status = $_POST['relationship_status'];
    $hobbies = $_POST['hobbies'];
    
    // Check if any required fields are empty or if there are issues with file uploads
    if (empty($home_town) || empty($contact_info) || empty($education) || empty($employment) || empty($relationship_status) || empty($hobbies) || empty($_FILES)) {
        $error_message = "Please fill in all required fields and upload files before submitting.";
        redirectTo('profile_setup', ['profile_setup_error' => $error_message]);
    }

    // Validate file type and size
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $profilePicExtension = strtolower(pathinfo($profilePic, PATHINFO_EXTENSION));
    $coverPicExtension = strtolower(pathinfo($coverPic, PATHINFO_EXTENSION));

    if (!in_array($profilePicExtension, $allowedExtensions) || !in_array($coverPicExtension, $allowedExtensions)) {
        $error_message = "Invalid file type. Please upload an image (jpg, jpeg, png, gif).";
        redirectTo('profile_setup', ['profile_setup_error' => $error_message]);
    } elseif ($_FILES['profile_picture']['size'] > $maxFileSize || $_FILES['cover_picture']['size'] > $maxFileSize) {
        $error_message = "File size exceeds the limit (5MB).";
        redirectTo('profile_setup', ['profile_setup_error' => $error_message]);
    } elseif (move_uploaded_file($profile_picture, $profilePic) && move_uploaded_file($cover_picture, $coverPic)) {
        // Files uploaded successfully
        $userId = $_SESSION['session_id'];
        $user = new User();

        if ($user->profileSetup($userId, $home_town, $contact_info, $education, $employment, $relationship_status, $hobbies, $profilePic, $coverPic)) {
            echo 'Data saved successfully';
            redirectTo('home');
        } else {
            redirectTo('error');
            echo 'Failed to save data to the database';
        }
    } else {
        $error_message = 'Failed to upload files';
        redirectTo('profile_setup', ['profile_setup_error' => $error_message]);
    }
}
