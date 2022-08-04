<?php

namespace fw;

class ErrorHandler{

    public function __construct(){
        if(DEBUG){
            error_reporting(E_ALL);
        }else{
            error_reporting(0);
        }
        set_error_handler([$this, 'errorHandler']);//register custom handler
        ob_start(); //prevent browser output
        register_shutdown_function([$this, 'fatalErrorHandler']); //at the end of the script
        set_exception_handler([$this, 'exeptionHandler']); //fires when throw exeption
    }

    public function errorHandler($errno, $errstr, $errfile, $errline){
        echo 'function errorHandler<br>';
        $this->logErrors($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
        return true; 
    }

    protected function displayError($errno, $errstr, $errfile, $errline, $response = 503){
        http_response_code($response);
        if ($response == 404 && !DEBUG){
            require_once VIEWS . '/errors/404.php';
            die();
        }
        if(DEBUG){
            require_once VIEWS . '/errors/dev.php';
        }else{
            require_once VIEWS . '/errors/prod.php';
        }
        die();
    }

    public function fatalErrorHandler(){
        $error = error_get_last();
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)){
            $this->logErrors($error['message'], $error['file'], $error['line']);
            ob_end_clean();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        }else{
            ob_end_flush();
        }
    }

    public function exeptionHandler($e){
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function logErrors($message = '', $file = '', $line = ''){
        error_log("[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} |
         Строка: {$line}\n===============\n", 3, ROOT . '/tmp/errors/errors.log');
    }

}