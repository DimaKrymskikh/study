<?php

namespace controllers;

use base\BaseController;

/**
 * Отрисовывает главную страницу
 */
class CommonController extends BaseController 
{
    protected const TEMPLATE = 'layout/template.php';
    protected const BASE_URL = __DIR__ . '/../views/';
    protected const FILE_FOLDER = 'common/';


    public function indexAction(): string
    {
        $this->title = 'Главная страница';

        return $this->render('main.php', []);
    }
}
