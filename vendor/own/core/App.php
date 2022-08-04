<?php

namespace fw;

class App{

    public static $appConteiner; //here object(fw\Registry)

    public function __construct(){
        $query = trim($_SERVER['QUERY_STRING'], '/');
        session_start();
        self::$appConteiner = Registry::instance();
        $this->pullParametrs();
        new ErrorHandler();
        Router::dispatch($query); //pass url to router
    }

    protected function pullParametrs(){
        $params = require_once CONF . '/parmetrs.php';
        if (!empty($params)){
            foreach ($params as $k => $v){
                self::$appConteiner->writeParameters($k, $v);
            }
        }
    }

}