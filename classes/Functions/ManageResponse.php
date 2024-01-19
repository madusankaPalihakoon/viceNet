<?php
namespace Functions;

use Configuration\SessionManager;

require_once __DIR__.'/../../vendor/autoload.php';

class ManageResponse {
    public static function sendResponse($response): void {
        // Check if the response is an array
        if (!is_array($response)) {
            // If not an array, create an error response
            $response = ['error' => 'Invalid response data'];
        }

        // Convert the response to JSON
        $jsonResponse = json_encode($response);

        // Check for JSON encoding errors
        if ($jsonResponse === false) {
            // If encoding fails, create an error response
            $jsonResponse = json_encode(['error' => 'JSON encoding error']);
        }

        // Set the appropriate headers for JSON response
        header('Content-Type: application/json');

        // Output the JSON response and exit
        echo $jsonResponse;
        exit;
    }

    public static function handleInvalidRequest() : void {
        // Set the appropriate headers for JSON response
        header('Content-Type: application/json');
        $response = ['status' => false , 'error' => 'Invalid Request Method !'];
        self::sendResponse( $response);
        exit;
    }

    public static function handleInvalidAction() : void {
        // Set the appropriate headers for JSON response
        header('Content-Type: application/json');
        $response = ['status' => false , 'error' => 'Invalid Action !'];
        self::sendResponse( $response);
        exit;
    }

    public static function handleUnknownError() : void {
        // Set the appropriate headers for JSON response
        header('Content-Type: application/json');
        SessionManager::setMessage('Something went wrong, Please try again later!');
        $response = ['status' => false , 'error' => 'Something went wrong, Please try again later!'];
        self::sendResponse( $response);
        exit;
    }

    public static function handleSessionTimeOut($userID) :void {
        if ( is_null($userID)) {
            // Set the appropriate headers for JSON response
            header('Content-Type: application/json');
            SessionManager::setMessage('Requested Time Out!');
            $response = ['status' => false , 'error' => 'Requested Time Out!'];
            self::sendResponse( $response);
            exit;
        }
    }
}