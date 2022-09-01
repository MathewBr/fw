<?php

namespace app\models;

use fw\App;

class Breadcrumbs{

    public static function getBreadcrumbs($category_id, $name = ''){
        $categories = App::$appContainer->getParameter('categories');
        $arr_breadcrumbs = self::getParts($categories, $category_id);
        $breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>";
        if ($arr_breadcrumbs){
            foreach ($arr_breadcrumbs as $alias => $title){
                $breadcrumbs .= "<li><a href='" . PATH . "/category/{$alias}'>{$title}</a></li>";
            }
        }
        if ($name){
            $breadcrumbs .= "<li>$name</li>";
        }
        return $breadcrumbs;
    }

    public static function getParts($categories, $id){
        if (!$id) return false;
        $breadcrumbs = [];
        while (isset($categories[$id])){
            $breadcrumbs[$categories[$id]['alias']] = $categories[$id]['title'];
            $previous_id = $id;
            $id = $categories[$id]['parent_id'];
            //loop protection
            if ($previous_id == $id) break;
        }
        return array_reverse($breadcrumbs, true);
    }

}