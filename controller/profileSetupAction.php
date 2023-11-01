<?php

use ViceNet\Classes\User;

require_once __DIR__ . "/../functions/profileSetupErrorChecker.php";
require_once __DIR__ . "/../functions/redirectFunction.php";
require_once __DIR__ . '/../config/SessionConfig.php';
require_once __DIR__ . '/../classes/Classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get Selected Image Details
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

  $validImageExtension = ['jpg', 'jpeg', 'png'];

  if (!in_array($profileImageExtension, $validImageExtension) || !in_array($coverImageExtension, $validImageExtension)) {
    echo 'Invalid Image Extension';
  } else if ($profilePicSize > 1000000) {
    echo 'Profile Picture Size Is Too Large';
  } else if ($coverPicSize > 1000000) {
    echo 'Cover Picture Size Is Too Large';
  } else if( move_uploaded_file($profilePicTmpName, $profilePicUploadDir . '/' . $profilePicName) && move_uploaded_file($coverPicTmpName, $coverPicUploadDir . '/' . $coverPicName) ) {
    // When Move uploaded file Success
    $user = new User();

    $userId = $_SESSION['session_id'];
    $home_town = $_POST['home_town'] ;
    $contact_info = $_POST['contact_info'] ;
    $education = $_POST['education'] ;
    $employment = $_POST['employment'] ;
    $relationship_status = $_POST['relationship_status'] ;
    $hobbies = $_POST['hobbies'] ;
    $profilePic = $profilePicName ;
    $coverPic = $coverPicName ;
    $user->profileSetup( $userId, $profilePic, $coverPic, $home_town, $contact_info, $education, $employment, $relationship_status, $hobbies );
  }
}
