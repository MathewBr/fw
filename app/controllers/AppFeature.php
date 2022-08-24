<?php

namespace app\controllers;
use app\models\AppModel;
use app\widgets\currency\Currency;
use fw\App;
use fw\base\Controller;

class AppFeature extends Controller {

    public function __construct($route){
        parent::__construct($route);
        new AppModel();

        $currencies = Currency::getCurrencies();
        App::$appContainer->writeParameters('currencies', $currencies); //write in Registry possible currencies
        App::$appContainer->writeParameters('currency', Currency::getCurrency($currencies)); //write in Registry the active currency
    }

}