<?php

namespace fw\base;

abstract class Controller{

    public $route; //array with all route data

    public $viewFolder; //separately
    public $viewfile;//corresponds to the action
    public $layout;
    public $prefix;//separately
    public $model;
    public $data = []; //transmitted data in view
    public $meta = []; //data for tag <meta>

    public function __construct($route){
        $this->route = $route;
        $this->viewFolder = lcfirst($route['controller']); //at the same time is folder name with views for corresponding controller
        $this->viewfile = $route['action'];//file name corresponding to this action
        $this->prefix = $route['prefix'];
        $this->model = $route['controller'];//each controller has its own default model
    }

    public function passData($data){
        $this->data = $data;
    }

    public function setMeta($title='', $description='',$keywords=''){
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;
    }

    public function displayView(){
        $viewObject = new View($this->route, $this->layout, $this->viewfile, $this->meta);
        $viewObject->render($this->data);
    }

}