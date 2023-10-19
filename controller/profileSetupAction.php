<?php
// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access the values from the FormData object
    $profile_picture = $_FILES['profile_picture']; // For file upload
    $cover_picture = $_FILES['cover_picture']; // For file upload
    $home_town = $_POST['home_town'];
    $contact_info = $_POST['contact_info'];
    $education = $_POST['education'];
    $employment = $_POST['employment'];
    $relationship_status = $_POST['relationship_status'];
    $hobbies = $_POST['hobbies'];

    // Now, you can print or process these values as needed
    echo "Profile Picture:\n";
    print_r($profile_picture);

    echo "Cover Picture:\n";
    print_r($cover_picture);

    echo "Home Town: " . $home_town . "\n";
    echo "Contact Info: " . $contact_info . "\n";
    echo "Education: " . $education . "\n";
    echo "Employment: " . $employment . "\n";
    echo "Relationship Status: " . $relationship_status . "\n";
    echo "Hobbies: " . $hobbies . "\n";
} else {
    echo 'no';
}
