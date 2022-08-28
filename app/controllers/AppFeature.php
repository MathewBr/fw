<?php

namespace app\controllers;
use app\models\AppModel;
use app\widgets\currency\Currency;
use fw\App;
use fw\base\Controller;
use fw\Cache;

class AppFeature extends Controller {

    public function __construct($route){
        parent::__construct($route);
        new AppModel();

        $currencies = Currency::getCurrencies();
        App::$appContainer->writeParameters('currencies', $currencies); //write in Registry possible currencies
        App::$appContainer->writeParameters('currency', Currency::getCurrency($currencies)); //write in Registry the active currency

        //in order not to constantly access the cache file or database to get an array of product categories - write them to the container
        App::$appContainer->writeParameters('categories', self::cacheCategory());//high volume can slow down the application
    }

    //caching product categories
    public static function cacheCategory(){
        $cache = Cache::instance();
        $categories = $cache->get('categories');
        if (!$categories){
            $categories = \R::getAssoc("SELECT * FROM category");
            $cache->set('categories', $categories);
        }
        return $categories;
    }

}