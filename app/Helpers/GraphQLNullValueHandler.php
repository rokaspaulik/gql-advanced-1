<?php

namespace App\Helpers;

class GraphQLNullValueHandler
{
    public static function handle(string $type): mixed {
        return match ($type) {
            'id' => 1,
            'int' => 0,
            'json' => [],
            'boolean' => false,
            'string' => '',
        };
    }
}
