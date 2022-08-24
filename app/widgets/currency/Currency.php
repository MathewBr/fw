<?php

namespace app\widgets\currency;

use fw\App;
use RedBeanPHP\R;

class Currency{

    protected $tpl; //widget template
    protected $currencies;
    protected $currency;

    public function __construct(){
        $this->tpl = __DIR__ . '/currency_tpl/currency.php';//hard directive can be changed, see language widget
        $this->run();
    }

    protected function run(){
        $this->currencies = App::$appContainer->getParameter('currencies');
        $this->currency = App::$appContainer->getParameter('currency');
        echo $this->getHtml();
    }

    public static function getCurrencies(){//to be able to call a method without creating an object
        return R::getAssoc("SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC");
    }

    public static function getCurrency($currencies){
        if (isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)){
            $key = $_COOKIE['currency'];
        }else{
            $key = key($currencies);
        }
        $currency = $currencies[$key];
        $currency['code'] = $key;
        return $currency;
    }

    protected function getHtml(){
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }

}