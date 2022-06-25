<?php

namespace base;

/**
 * Класс для обработки данных запроса
 */
class UtilsRequest
{
    /**
     * Обрабатывает целочисленную величину $name запроса.
     * Если $name в запросе отсутствует, то $name присваивается дефолтное значение
     * @param string $name - переменная запроса
     * @param int $defaultValue - дефолтное значение
     * @return int
     */
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
