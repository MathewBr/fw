<?php

namespace app\controllers\admin;

use fw\Cache;

class CacheController extends AppFeature {

    public function indexAction(){
        $this->setMeta('Очистка кэша');
    }

    public function deleteAction(){
        $key = isset($_GET['key']) ? $_GET['key'] : null;
        $cache = Cache::instance();
        switch ($key){
            case 'category':
                $cache->delete('categories');
                $cache->delete('menu_Category');
                break;
            case 'filter':
                $cache->delete('filter_groups');
                $cache->delete('filter_attrs');
                break;
        }
        $_SESSION['success'] = 'Выбранный кэш удалён';
        redirect();
    }

}