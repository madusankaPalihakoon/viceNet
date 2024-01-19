<?php

namespace Functions;

class InputSanitizer {
    public static function sanitizeString($input) {
        // Use FILTER_SANITIZE_STRING filter to remove HTML tags and encode special characters
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    // You can add more methods for different types of input sanitization if needed

    // Example: Sanitize and validate an email address
    public static function sanitizeEmail($email) {
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Additional validation logic can be added if needed
        return $sanitizedEmail;
    }

    // Add more methods for different types of input as necessary
}
