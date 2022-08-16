<?php

namespace app\controllers;

use fw\App;
use fw\Cache;

class MainController extends AppFeature {

    public function indexAction(){

        $this->setMeta(App::$appContainer->getParameter('shop_name'), 'Описание...', 'Ключевые слова');//example use parameters
        $brands = \R::find('brand', 'LIMIT 3');

        $hits = \R::find('product', "hit = '1' AND status = '1' LIMIT 8");//the hits and status values in the database are string, so this in ''

        $this->passData(compact('brands', 'hits'));



//        $posts = \R::findAll('test');
//        $post = \R::findOne('test', 'id = ?', [2]);
//        $this->passData(compact('posts', 'post'));//pass data to view or layouts

//        $names = ['Andrey', 'Jane'];
//        $cache = Cache::instance();
//        $cache->set('test', $names);
//        $cache->delete('test');
//        $data = $cache->get('test');
    }
}