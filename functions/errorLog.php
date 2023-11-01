<?php
    // Log the error message to user_signup_log.txt
    function errorLog($e) {
        // Define the error log file path using an absolute path
        $error_log = __DIR__ .'/var/log/errorLog.log';
        
        $log_entry = $e->getMessage();
    
        $log_entry .= PHP_EOL;
    
        // Log the error using error_log, which handles logging to the specified file
        error_log($log_entry, 3, $error_log);
    
        header("Location: ../pages/error");
        exit;
    }