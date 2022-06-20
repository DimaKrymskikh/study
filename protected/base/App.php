<?php

namespace base;

use controllers\CommonController;
use base\PrintException;

class App 
{
    public static DBQuery $db;

    public function __construct(object $config) 
    {
        self::$db = new DBQuery($config->db);
    }
    
    public function run(): void
    {
        sleep(1);
        
        try {
            $this->router();
        } catch (PrintException) {}
    }

    /**
     * Задаёт маршрузацию
     * @return void
     */
    private function router(): void
    {
        $truncatedUri = trim(parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH), '/');
        
        if ($truncatedUri === '') {
            echo (new CommonController())->indexAction();
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
