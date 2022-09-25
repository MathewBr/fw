<?php

namespace app\controllers\admin;

use fw\App;

class MainController extends AppFeature{

    public function indexAction(){
        $countNewOrders = \R::count('order', "status = '0'");
        $countUsers = \R::count('user');
        $countProducts = \R::count('product');
        $countCategories = \R::count('category');
        $this->setMeta('Панель управления');
        $this->passData(compact('countNewOrders', 'countUsers', 'countProducts', 'countCategories'));
    }

}