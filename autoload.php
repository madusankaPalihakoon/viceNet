<?php

// Include Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// If you want to use your custom namespaces for autoloading, you can do so here.

// Example: Custom autoloading for Class namespace
spl_autoload_register(function ($className) {
    // Define the base directory for the "Class" namespace
    $baseDir = __DIR__ . '/classes/';

    // Replace backslashes with directory separators
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // Build the full path to the class file
    $classFile = $baseDir . $className . '.php';

    // Check if the class file exists and require it
    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// You can define similar autoloaders for other custom namespaces like "PHPMailer" and "AjaxFunction" as needed.
