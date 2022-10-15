<?php

namespace app\controllers\admin;

use app\models\admin\Currency;

class CurrencyController extends AppFeature{

    public function indexAction(){
        $currencies = \R::findAll('currency');
        $this->setMeta('Валюты магазина');
        $this->passData(compact('currencies'));
    }

    public function addAction(){
        if(!empty($_POST)){
            $currency = new Currency();
            $data = $_POST;
            $currency->selectiveLoading($data);
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            if(!$currency->validate($data)){
                $currency->showValidageErors();
                redirect();
            }
            if($currency->saveInDbase('currency')){
                $_SESSION['success'] = 'Валюта добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая валюта');
    }

    public function editAction(){
        if(!empty($_POST)){
            $id = $this->getRequestID(false);
            $currency = new Currency();
            $data = $_POST;
            $currency->selectiveLoading($data);
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            if(!$currency->validate($data)){
                $currency->showValidageErors();
                redirect();
            }
            if($currency->update('currency', $id)){
                $_SESSION['success'] = "Изменения сохранены";
                redirect();
            }
        }

        $id = $this->getRequestID();
        $currency = \R::load('currency', $id);
        $this->setMeta("Редактирование валюты {$currency->title}");
        $this->passData(compact('currency'));
    }

    public function deleteAction(){
        $id = $this->getRequestID();
        $currency = \R::load('currency', $id);
        \R::trash($currency);
        $_SESSION['success'] = "Изменения сохранены";
        redirect();
    }

}