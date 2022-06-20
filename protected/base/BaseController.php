<?php

namespace base;

class BaseController 
{
    protected string $title = '';

    protected function render(string $file, array $params = []): string 
    {
        $content = $this->renderContent($file, $params);
        return $this->renderTemplate([
                'content' => $content,
                'title' => $this->title,
                'isLogin' => Cookie::isLogin()
            ]);
    }
    
    protected function renderTemplate(array $params): string 
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        require_once static::BASE_URL . static::TEMPLATE;
        return ob_get_clean();
    }

    protected function renderContent(string $file, array $params = []): string
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        require_once $this->getFileUrl($file);
        return ob_get_clean();
    }
    
    protected function conditionalRender(string $file, array $data): string
    {
        return filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') ? $this->renderContent($file, $data) : $this->render($file, $data);
    }
    
    private function getFileUrl($file): string
    {
        return static::BASE_URL . static::FILE_FOLDER . $file;
    }
}
