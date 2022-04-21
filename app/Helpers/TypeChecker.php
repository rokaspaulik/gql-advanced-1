<?php

namespace App\Helpers;

use TypeError;

class TypeChecker
{
    /**
     * Check if value is JSON.
     *
     * @param mixed $value
     * 
     * @return boolean
     */
    public static function isJSON(mixed $value): bool {
        if (gettype($value) !== 'string') {
            return false;
        }

        try {
            json_decode($value);
        } catch (TypeError $e) {
            return false;
        }

        return json_last_error() === JSON_ERROR_NONE;
    }
}
