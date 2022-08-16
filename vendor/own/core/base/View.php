<?php

namespace fw\base;

class View{
    /*
    * same properties as in base Controller + layout
    * some properties from the base Controller can be overridden and added ($route, $layout='', $view='', $meta)
    * class will be called by the Controller
    */
    public $route; //array with all route data
    public $viewFolder; //separately
    public $viewFile;
    public $layout;
    public $prefix;//separately
    public $model;
//    public $data = [];//in this case we do not use
    public $meta = []; //data for tag <meta>

    public function __construct($route, $layout='', $view='', $meta){
        $this->route = $route;
        $this->viewFolder = lcfirst($route['controller']); //at the same time is folder name with views for corresponding controller
        $this->viewFile = $view;
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

    public function render($data){ //display a view in layout
        if (is_array($data)) extract($data); //if necessary, unpack the array with data
        //preparing named meta tags
       $insertedMeta = '';//this variable insert in the corresponding layout.php
       foreach ($this->meta as $name => $metaContent){
           if ($metaContent){
               if ($name == 'title') {
                   $insertedMeta .= "<title>$metaContent</title>";
               }else{
                   $insertedMeta .= "<meta name='{$name}' content='{$metaContent}'>";
               }
           }
       }

        //preparing view for display
        $viewPath = str_replace('\\', '/',APP . "/views/{$this->prefix}{$this->viewFolder}/{$this->viewFile}.php");
        if (is_file($viewPath)){
            ob_start();
            require_once $viewPath;
            $content = ob_get_clean();//this variable insert in the corresponding layout.php
        }else{
            throw new \Exception("Не найден файл вида {$this->viewFile}", 500);
        }

        //connect the layout
        if (false !== $this->layout){
            $layoutPath = str_replace('\\', '/',APP . "/views/layouts/{$this->layout}.php");
            if (is_file($layoutPath)){
                require_once $layoutPath;
            }else{
                throw new \Exception("Не найден шаблон {$this->layout}", 500);
            }
        }
    }

    //alternative way
    public function getMeta(){
        $output = '<title>' . $this->meta['title'] . '</title>';
        $output .= '<meta name="description" content="' . $this->meta['description'] . '">';
        $output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">';
        return $output;
    }
}