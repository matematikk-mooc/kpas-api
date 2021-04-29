<?php

namespace App\Utils;

class Token 
{
    /**
     * Generates a random alphanumeric string from a cryptographically secure random source.
     */
    public static function generate(int $length) {
        $bytes = random_bytes($length);
        return base64_encode($bytes);
    } 
}