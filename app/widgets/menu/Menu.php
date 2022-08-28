<?php

namespace app\widgets\menu;

use fw\App;
use fw\Cache;

class Menu{

    protected $data;
    protected $tree;
    protected $menuHtml;
    protected $tpl;
    protected $container = 'ul';
    protected $class = 'menu';
    protected $table = 'category';
    protected $cache = 3600;
    protected $cacheKey = 'menu_Category';
    protected $attrs = [];
    protected $prepend = '';

    public function __construct($options = []){
        $this->tpl = WIDGETS . '/menu/menu_category/menu_default.php'; //default template
        $this->getOptions($options);
        $this->run();
    }

    protected function getOptions($options){
        foreach ($options as $k => $v){
            if (property_exists($this, $k)){
                $this->$k = $v;
            }
        }
    }

    protected function run(){
        $cache = Cache::instance();//this is singleton
        $this->menuHtml = $cache->get($this->cacheKey);//if there is a cache and it is not expired, it will be returned, otherwise returned false
        if (!$this->menuHtml){
            $this->data = App::$appContainer->getParameter('categories');
            //if for some reason there is no category array in the container, we will get it from the database
            if (!$this->data){
                $this->data = \R::getAssoc("SELECT * FROM {$this->table}");
            }
            $this->tree = $this->buildTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);

            if ($this->cache){
                $cache->set($this->cacheKey, $this->menuHtml, $this->cache);
            }
        }
        $this->output();
    }

    protected function output(){
        $attributes = '';
        if (!empty($this->attrs)){
            foreach ($this->attrs as $name => $val){
                $attributes .= " $name=\"$val\" ";
            }
        }
        echo "<{$this->container} class=\"{$this->class}\" $attributes>";
            echo $this->prepend;
            echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    //works if the data is in the form: id | item | parent_id
    protected function buildTree(){
        $tree = [];
        $data = $this->data;
        foreach ($data as $id => &$node){
            if (!$node['parent_id']){
                //if parent_id == 0 write link in new array
                $tree[$id] = &$node;
            }else{
                //change the structure of the original (data) array using links
                $data[$node['parent_id']]['child'][$id] = &$node;
            }
        }
        return $tree;
    }

    protected function getMenuHtml($tree, $tab = ''){
        $str = '';
        foreach ($tree as $id => $category){
            $str .= $this->wrapItem($category, $tab, $id);
        }
        return $str;
    }

    protected function wrapItem($item, $tab, $id){
        ob_start();
        require $this->tpl; //require_once not suitable as multiple connection
        return ob_get_clean();
    }

}