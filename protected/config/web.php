<?php

/* 
 * Конфигурация системы
 */

$db = require_once __DIR__ . '/db.php';

return (object) [
    'db' => $db,
];
