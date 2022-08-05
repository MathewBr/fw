<?php

namespace app\controllers;

use fw\App;

class MainController extends AppFeature {

    public function indexAction(){
//      $this->setMeta(App::$appContainer->getParameter('shop_name'), 'Описание...', 'Ключевые слова');//example use parameters
        $this->passData(['name' => 'Вася', 'age' => 19]);//example pass data to View
        //or use function compact()
//        $name = 'John';
//        $age = 30;
//        $this->passData(compact('name', 'age'));
    }
}