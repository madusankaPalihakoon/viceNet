<?php
require_once __DIR__.'/../classes/Classes/Post.php';
use ViceNet\Classes\Post;
require_once __DIR__.'/../config/SessionConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = uploadPostToServer();
    
    if ($result['status'] == 'success') {
        $fileName = $result['name'];
        $caption = $_POST['caption'];
        // $caption = isset($_POST['caption']) && $_POST['caption'] !== "" ? $_POST['caption'] : null;
        if (!uploadPostToDatabase( $fileName, $caption)) {
            $result = ['status' => 'fail' , 'message' => 'something went wrong, please try again later!'];
            sendResponse( $result);
        } else {
            $result = ['status' => 'success' , 'message' => 'successfully upload post'];
            sendResponse( $result);
        }
    } else {
        sendResponse( $result);
    }
    

} else {
    $result = ['status' => 'fail', 'error' => 'invalid request!'];
    sendResponse( $result);
}

function uploadPostToServer() {
    // Check if a file was selected
    if (isset($_FILES["photo"])) {
        $uploadDir = '../assets/uploads/post/'; // The directory to store uploaded files

        // Generate a unique identifier for the file name
        $uniqueID = uniqid();

        // Get the original file extension
        $originalExtension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);

        // Set the new file name with the unique identifier and original extension
        $fileName = $uniqueID . '.' . $originalExtension;

        // Set the full path for the uploaded file
        $filePath = $uploadDir . $fileName;

        // Define allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        // Check if the uploaded file type is allowed
        if (in_array(strtolower($originalExtension), $allowedTypes)) {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $filePath)) {
                $result = ['status' => 'success' , 'name' => $fileName];
                return $result;
            } else {
                $result = ['status' => 'fail', 'message' => 'Error uploading the file.'];
                return $result;
            }
        } else {
            $result = ['status' => 'fail', 'message' => 'Invalid file type.'];
            return $result;
        }
    } else {
        $result = ['status' => 'fail', 'message' => 'No file selected.'];
        return $result;
    }
}

function uploadPostToDatabase( string $fileName, string $caption) : bool {
    $post = new Post();
    $userId = $_SESSION['session_id'];
    return $post->setPost($userId, $fileName, $caption);
}

function sendResponse(array $response) {
    header('Content-Type: application/json');
    echo json_encode($response);
}
