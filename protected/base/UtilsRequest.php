<?php

namespace base;

class UtilsRequest
{
    public static function validateInt(string $name, int $defaultValue): int
    {
        $type = match (filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
            'GET' => INPUT_GET,
            'POST' => INPUT_POST,
        };
        $param = filter_input($type, $name, FILTER_VALIDATE_INT);
        return isset($param) ? $param : $defaultValue;
    }
}
