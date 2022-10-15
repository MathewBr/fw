<?php

namespace fw;

class Db{

    use TSingletone;

    protected function __construct(){
        $db = require_once CONF . '/config_db.php';
        class_alias('\RedBeanPHP\R', '\R'); //according to instructions redBeanPHP
        \R::setup($db['dsn'], $db['user'], $db['pass']);
        if (!\R::testConnection()){
            throw new \Exception("Нет соединения с базой данных", 500);
        }
        \R::freeze(true);//disable changes data base on the fly
        if (DEBUG){
            \R::debug(true, 1);
        }

        //redbeanPHP не допускает имена таблиц с _, обходной маневр, см. документацию
        \R::ext('xdispense', function ($type){
            return \R::getRedBean()->dispense($type);
        });
    }

}