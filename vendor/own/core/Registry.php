<?php

namespace fw;

class Registry{

    use TSingletone;

    public static $parameters = [];

    public function writeParameters($key, $value){
        self::$parameters[$key] = $value;
    }

    public function getParameter($key){
        if (isset(self::$parameters[$key])){
            return self::$parameters[$key];
        }
        return null;
    }

    public function getAllParameters(){
        return self::$parameters;
    }

}