<?php

require_once __DIR__.'/../classes/Classes/Post.php';
require_once __DIR__.'/../config/SessionConfig.php';

use ViceNet\Classes\Post;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve parameters from the URL
    $likeStatus = $_GET['likeStatus'] ?? '';
    $postId = $_GET['postID'] ?? '';
    $current_user = $_SESSION['session_id'];

    // create post class new instance
    $post = new Post();

    if ($likeStatus === 'like') {
        $status = 1;
    } elseif ($likeStatus === 'dislike') {
        $status = 0;
    } else {
        // Invalid likeStatus value
        die(json_encode(['error' => 'Invalid likeStatus value']));
    }

    // Use handleLike instead of setLike
    $post->setLike($current_user, $postId, $status);

    // Send response to client side
    $response = ['status' => 'success', 'message' => 'Like status updated successfully'];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle other HTTP methods (optional)
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
}
