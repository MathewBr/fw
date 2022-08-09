<?php

namespace app\controllers;

use fw\App;

class MainController extends AppFeature {

    public function indexAction(){
//      $this->setMeta(App::$appContainer->getParameter('shop_name'), 'Описание...', 'Ключевые слова');//example use parameters
        $posts = \R::findAll('test');
        $post = \R::findOne('test', 'id = ?', [2]);
        $this->passData(compact('posts', 'post'));
    }
}