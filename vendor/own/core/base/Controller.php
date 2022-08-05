<?php

namespace fw\base;

abstract class Controller{

    public $route; //array with all route data

    public $viewFolder; //separately
    public $viewfile;//corresponds to the action
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

    public function setMeta($title='', $desc='',$keywords=''){
        $this->meta['title'] = $title;
        $this->meta['desc'] = $desc;
        $this->meta['keywords'] = $keywords;
    }

}