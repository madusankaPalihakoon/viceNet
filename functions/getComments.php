<?php
require_once __DIR__ . '/../classes/Classes/Post.php';
use ViceNet\Classes\Post;

// Check if the request method is POST or GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postId = $_REQUEST['post_id'] ?? '';

    $response = array();

    $post = new Post();

    $response = $post->getComment( $postId);

    sendJsonResponse($response);
}

function sendJsonResponse(array $data, int $statusCode = 200): void
{
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}