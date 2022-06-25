<?php

namespace base;

/**
 * Базовый класс для всех контроллеров
 * @property string $title - заголовок на вкладке страницы браузера
 */
class BaseController 
{
    protected string $title = '';

    /**
     * Отрисовывает контент вместе с шаблоном
     * @param string $file - файл с контентом
     * @param array $params - передаваемые в $file переменные
     * @return string - страница документа
     */
    protected function render(string $file, array $params = []): string 
    {
        $content = $this->renderContent($file, $params);
        return $this->renderTemplate([
                'content' => $content,
                'title' => $this->title,
                'isLogin' => Cookie::isLogin()
            ]);
    }
    
    /**
     * Создаёт шаблон
     * @param array $params - передаваемые в шаблон переменные
     * @return string - заготовка шаблона
     */
    protected function renderTemplate(array $params): string 
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        require_once static::BASE_URL . static::TEMPLATE;
        return ob_get_clean();
    }

    /**
     * Отрисовывает контент без шаблона
     * @param string $file - файл контента
     * @param array $params - передаваемые в контент переменные
     * @return string - блок контента на странице документа
     */
    protected function renderContent(string $file, array $params = []): string
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        require_once $this->getFileUrl($file);
        return ob_get_clean();
    }
    
    /**
     * При ajax-запросе будет отрисован только контент, при http-запросе вся страница документа
     * @param string $file - файл с контентом
     * @param array $data - передаваемые в $file переменные
     * @return string - вся страница документа или только блок контента на странице документа
     */
    protected function conditionalRender(string $file, array $data): string
    {
        return filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') ? $this->renderContent($file, $data) : $this->render($file, $data);
    }
    
    /**
     * Формирует url к файлу с контентом
     * @param type $file - файл с контентом
     * @return string
     */
    private function getFileUrl($file): string
    {
        return static::BASE_URL . static::FILE_FOLDER . $file;
    }
}
