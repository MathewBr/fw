<?php

namespace app\controllers;

use fw\App;

class CurrencyController extends AppFeature {

    public function changeCurrencyAction(){
        $queryCurrency = !empty($_GET['curr']) ? $_GET['curr'] : null;
        $possibleCurrencies = App::$appContainer->getParameter('currencies');
        $defaultCurrency = App::$appContainer->getParameter('currency');
        if ($queryCurrency && array_key_exists($queryCurrency, $possibleCurrencies)){
             $curr = $queryCurrency;
        }else{
            $curr = $defaultCurrency['code'];
        }
        setcookie('currency', $curr, time() + 3600*24*7, '/');
        redirect();
    }
}