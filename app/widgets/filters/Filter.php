<?php

namespace app\widgets\filters;

use fw\Cache;

class Filter{
    public $groups;
    public $attrs;
    public $tpl;

    public function __construct(){
        $this->tpl = __DIR__ . '/filter_tpl.php';
        $this->run();
    }

    protected function run(){
        $cache = Cache::instance();
        $this->groups = $cache->get('filter_groups');
        if (!$this->groups){
            $this->groups = $this->getCroups();
            $cache->set('filter_groups', $this->groups, 1);
        }

        $this->attrs = $cache->get('filter_attrs');
        if (!$this->attrs){
            $this->attrs = $this->getAttrs();
            $cache->set('filter_attrs', $this->attrs, 1);
        }
        $filters = $this->getHtml();
        echo $filters;
    }

    protected function getCroups(){
        return \R::getAssoc('SELECT id, title FROM attribute_group');
    }

    protected function getAttrs(){
        $arr = \R::getAssoc('SELECT * FROM attribute_value');
        $attrs = [];
        foreach ($arr as $k => $v){
            $attrs[$v['attr_group_id']][$k] = $v['value'];
        }
        return $attrs;
    }

    protected function getHtml(){
        ob_start();
        //to remember the choice
        $filter = self::getFilter();
        if (!empty($filter)){
            $filter = explode(',', $filter);
        }
        require $this->tpl;
        return ob_get_clean();
    }

    public static function getFilter(){
        $filter = null;
        if (!empty($_GET['filter'])){
            $filter = preg_replace("#[^\d,]+#", '', $_GET['filter']); //anything that is not a digit or a comma replace
            $filter = trim($filter, ',');
        }
        return $filter;
    }

}