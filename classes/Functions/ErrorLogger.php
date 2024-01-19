<?php

namespace Functions;

require_once __DIR__.'/../../vendor/autoload.php';

use Functions\Mail;

class ErrorLogger {

    private static $mailer;

    // Static initialization method to ensure mailer is initialized only once
    public static function initMailer() {
        // Ensure the mailer is initialized only once
        if (!isset(self::$mailer)) {
            self::$mailer = new Mail();
        }
    }

    public static function logError($e, $th, $logFileName) {
        // Initialize mailer
        self::initMailer();
        // Log file path
        $logFilePath = __DIR__ . '/../../var/log/'.$logFileName.'.log';
    
        // Create or append to the log file
        $logMessage = '[' . date('Y-m-d H:i:s') . ']';
    
        if ($e instanceof \Exception) {
            $logMessage .= ' Exception: ' . $e->getMessage();
        }
    
        if ($th instanceof \Throwable) {
            $logMessage .= ' Throwable: ' . $th->getMessage();
        }
    
        $logMessage .= PHP_EOL;
    
        // Save the log message to the log file
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    
        // Log additional information like stack trace, file, and line number if needed
        if ($e instanceof \Exception) {
            $logMessage .= 'File: ' . $e->getFile() . PHP_EOL;
            $logMessage .= 'Line: ' . $e->getLine() . PHP_EOL;
            $logMessage .= 'Stack Trace: ' . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
        }
    
        if ($th instanceof \Throwable) {
            $logMessage .= 'File: ' . $th->getFile() . PHP_EOL;
            $logMessage .= 'Line: ' . $th->getLine() . PHP_EOL;
            $logMessage .= 'Stack Trace: ' . PHP_EOL . $th->getTraceAsString() . PHP_EOL;
        }
    
        // Append additional information to the log file
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
        self::$mailer->sendErrorToAdmin($logFilePath, $logMessage);
    }
}
