<?php
require_once __DIR__ . '/../classes/Classes/Post.php';
use ViceNet\Classes\Post;

// // Create a new instance of PostNewFunction
$postNewFunction = new Post();

// Extract batch and row parameters from the request
$batchIndex = $_GET['batch'];

// Execute the processRowsInBatches method with specified batch and row indices
$responseData = $postNewFunction->getPost($batchIndex);

// Respond with JSON-encoded data
header('Content-Type: application/json');
echo json_encode($responseData);