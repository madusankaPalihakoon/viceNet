<?php
require_once __DIR__."/../classes/Classes/Post.php";
require_once __DIR__."/../config/SessionConfig.php";

use ViceNet\Classes\Post;

// Check if the request method is POST or GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get comment and post ID from the request
    $commentText = $_REQUEST['comment'] ?? '';
    $postId = $_REQUEST['id'] ?? '';
    $userId = $_SESSION['session_id'] ?? '';

    $response = [];

    if (empty($commentText)) {
        $response = [
            'status' => 'fail',
            'message' => 'Please enter a comment!',
        ];

        // Send the response as JSON
        sendJsonResponse($response);
    }

    // Create an instance of the Post class
    $post = new Post();

    // Set the comment and get the response
    $response = $post->setComment($userId, $postId, $commentText);

    // Send the response as JSON
    sendJsonResponse($response);
} else {
    // If the request method is not supported, return an error
    $response = [
        'status' => 'error',
        'message' => 'Unsupported request method',
    ];

    // Send the response as JSON
    sendJsonResponse($response, 400); // Bad Request
}

function sendJsonResponse(array $data, int $statusCode = 200): void
{
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}