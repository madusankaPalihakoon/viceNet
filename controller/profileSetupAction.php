<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form data here
    $home_town = $_POST['home_town'];
    $contact_info = $_POST['contact_info'];
    $education = $_POST['education'];
    $employment = $_POST['employment'];
    $relationship_status = $_POST['relationship_status'];
    $hobbies = $_POST['hobbies'];

    $profilePicDir = __DIR__."/../assets/uploads/profilePic"; // The folder where you want to store the images
    $coverPicDir = __DIR__."/../assets/uploads/coverPic"; // The folder where you want to store the images

    $profile_picture = $_FILES['profile_picture']['tmp_name'];
    $cover_picture = $_FILES['cover_picture']['tmp_name'];
    $profilePic = $profilePicDir . '/' . basename($_FILES['profile_picture']['name']);
    $coverPic = $coverPicDir . '/' . basename($_FILES['cover_picture']['name']);

    // Validate file type and size
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $profilePicExtension = strtolower(pathinfo($profilePic, PATHINFO_EXTENSION));
    $coverPicExtension = strtolower(pathinfo($coverPic, PATHINFO_EXTENSION));

    if (!in_array($profilePicExtension, $allowedExtensions) || !in_array($coverPicExtension, $allowedExtensions)) {
        echo 'Invalid file type. Please upload an image (jpg, jpeg, png, gif).';
    } elseif ($_FILES['profile_picture']['size'] > $maxFileSize || $_FILES['cover_picture']['size'] > $maxFileSize) {
        echo 'File size exceeds the limit (5MB).';
    } elseif (move_uploaded_file($profile_picture, $profilePic) && move_uploaded_file($cover_picture, $coverPic)) {
        // Files uploaded successfully
        // Connect to the database and insert form data
        $db = new mysqli("localhost", "root", "", "vicenet");

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $sql = "INSERT INTO user_data (home_town, contact_info, education, employment, relationship_status, hobbies, profile_pic_path, cover_pic_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssssss", $home_town, $contact_info, $education, $employment, $relationship_status, $hobbies, $profilePic, $coverPic);

        if ($stmt->execute()) {
            echo 'Data saved successfully';
        } else {
            echo 'Failed to save data to the database';
        }

        $stmt->close();
        $db->close();
    } else {
        echo 'Failed to move uploaded files';
    }
}