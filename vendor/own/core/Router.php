<?php

namespace fw;

class Router{
    protected static $routes = [];//list of routes
    protected static $route = [];//current route when a match is found

    public static function addRoute($regexp, $route = []){
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(){
        return self::$routes;
    }

    public static function getRoute(){
        return self::$route;
    }

    public static function dispatch($url){ //processes the url request
        if (self::matchRoute($url)){
            $controller = 'app\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller'; //exemple, app\controllers\admin\MainController
            if (class_exists($controller)){
                $controllerObject = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($controllerObject, $action)){
                    $controllerObject->$action();
                }else{
                    throw new \Exception("Метод {$controller}::{$action} не найден.", 404);
                }
            }else{
                throw new \Exception("Контроллер {$controller} не найден.", 404);
            }
        }else{
            throw new \Exception("Страница не найдена", 404);
        }
    }

    public static function matchRoute($url){ //looks for a match in the self::$routes
        foreach (self::$routes as $pattern => $route){
            if (preg_match("#{$pattern}#", $url, $matches)){
                foreach ($matches as $k => $match){
                    if (is_string($k)){
                        $route[$k] = $match;//add to route array
                    }
                }
                if (empty($route['action'])){
                    $route['action'] = 'index'; //default action
                }
                if (empty($route['prefix'])){
                    $route['prefix'] = ''; //default prefix
                }else{
                    $route['prefix'] .= '\\';//exemple, admin\
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    //CamelCase - for controllers
    protected static function upperCamelCase($name){
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    //camelCase - for actions
    protected static function lowerCamelCase($name){
        return lcfirst(self::upperCamelCase($name));
    }
}