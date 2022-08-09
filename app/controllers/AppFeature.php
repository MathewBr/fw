<?php

namespace app\controllers;
use app\models\AppModel;
use fw\base\Controller;

class AppFeature extends Controller {

    public function __construct($route){
        parent::__construct($route);
        new AppModel();
    }

}