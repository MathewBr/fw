<?php

namespace fw\base;

class View{
    /*
    * same properties as in base Controller + layout
    * some properties from the base Controller can be overridden and added ($route, $layout='', $view='', $meta)
    * class will be called by the corresponding controller (exemple, MainController)
    */
    public $route; //array with all route data
    public $viewFolder; //separately
    public $view;
    public $layout;//
    public $prefix;//separately
    public $model;
    public $data = []; //transmitted data in view
    public $meta = []; //data for tag <meta>

    public function __construct($route, $layout='', $view='', $meta){
        $this->route = $route;
        $this->viewFolder = lcfirst($route['controller']); //at the same time is folder name with views for corresponding controller
        $this->view = $view;
        $this->prefix = $route['prefix'];
        $this->model = $route['controller'];
        $this->meta = $meta;
        /*
         * layout can be disabled, overridden, or use the default if init.php ("DEF_LAY")
         * */
        if ($layout === false){
            $this->layout = false; //disabled
        }else{
            $this->layout = $layout ?: DEF_LAY; // overridden or default
        }
    }

}