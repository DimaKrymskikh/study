<?php

namespace base;

use controllers\CommonController;
use base\PrintException;

/**
 * Базовый класс проекта
 * Выполняет постоянное соединение с базой
 * Реализует маршрутизацию
 * @property DBQuery $db - хранит соединение с базой
 */
class App 
{
    public static DBQuery $db;

    public function __construct(object $config) 
    {
        // Создаём соединение с базой
        self::$db = new DBQuery($config->db);
    }
    
    /**
     * Находит нужный контроллер и экшен
     * @return void
     */
    public function run(): void
    {
        // sleep для проекта не нужен, но можно раскомментировать, чтобы работа приложения походила на реальный сайт.
        // Можно будет увидеть лоадер.
//        sleep(1);
        
        // Перехватываем исключения такие как 'Страница не найдена'
        try {
            $this->router();
        } catch (PrintException) {}
    }

    /**
     * Задаёт маршрутизацию
     * @return void
     */
    private function router(): void
    {
        $truncatedUri = trim(parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH), '/');
        
        if ($truncatedUri === '') {
            echo (new CommonController())->indexAction();
        } else if ($truncatedUri === 'error/index') {
            // Бросаем исключение, чтобы экшен был вызван с параметром
            throw new PrintException('Страница не найдена');
        } else {
            $arrayUri = preg_split('/\//', $truncatedUri);
            if (count($arrayUri) !== 2) {
                throw new PrintException('Страница не найдена');
            }
            list ($controller, $action) = $arrayUri;
            $classController = 'controllers\\' . ucfirst($controller) . 'Controller';
            $functionAction = $action . 'Action';
            if (class_exists($classController) && method_exists($classController, $functionAction)) {
                echo [new $classController, $functionAction]();
            } else {
                throw new PrintException('Страница не найдена');
            }
        }
    }
}
