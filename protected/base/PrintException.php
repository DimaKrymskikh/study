<?php

namespace base;

use controllers\ErrorController;

class PrintException extends \Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null) 
    {
        echo (new ErrorController())->indexAction($message);
        parent::__construct($message, $code, $previous);
    }
}
