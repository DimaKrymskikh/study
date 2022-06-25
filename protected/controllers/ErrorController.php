<?php

namespace controllers;

use base\BaseController;

/**
 * Используется для отрисовки пользовательского исключения
 */
class ErrorController extends BaseController 
{
    protected const TEMPLATE = 'layout/template.php';
    protected const BASE_URL = __DIR__ . '/../views/';
    protected const FILE_FOLDER = 'error/';
    
    public function indexAction(string $messageError): string
    {
        $this->title = 'Ошибка';
        return $this->conditionalRender('error.php', [
            'messageError' => $messageError
        ]);
    }
}
