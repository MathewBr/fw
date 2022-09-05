<?php

namespace app\models;

use fw\App;

class Cart extends AppModel{

    public function addToCart($product, $qty = 1, $modification = null){
        if (!isset($_SESSION['cart.currency'])){
            $_SESSION['cart.currency'] = App::$appContainer->getParameter('currency');
        }

        if ($modification){
            $ID = "{$product->id}-{$modification->id}";
            $title = "{$product->title} ({$modification->title})";
            $price = $modification->price;
        }else{
            $ID = $product->id;
            $title = $product->title;
            $price = $product->price;
        }

        if (isset($_SESSION['cart'][$ID])){
            $_SESSION['cart'][$ID]['qty'] += $qty;//if the product is already in session, add the specified amount
        }else{
            $_SESSION['cart'][$ID] = [
                'qty' => $qty,
                'title' => $title,
                'alias' => $product->alias,
                'price' => $price * $_SESSION['cart.currency']['value'],
                'img' => $product->img,
            ];
        }

        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * ($price * $_SESSION['cart.currency']['value']) : $qty * ($price * $_SESSION['cart.currency']['value']);

    }

    public function deleteItem($id){
        $qtyMinus = $_SESSION['cart'][$id]['qty'];
        $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.qty'] -= $qtyMinus;
        $_SESSION['cart.sum'] -= $sumMinus;
        unset($_SESSION['cart'][$id]);
    }

    public static function recalculate($newCurrencyCode, $possibleCurrencies){
//        $objNewCurrency = \R::findOne('currency', 'code = ?', [$newCurrency]);
        if (isset($_SESSION['cart.currency'])){// cart.currency is the current currency
            if ((int) $_SESSION['cart.currency']['base']){
                $_SESSION['cart.sum'] *= $possibleCurrencies[$newCurrencyCode]['value'];
            }else{
                //cast to the base type and multiply
                $_SESSION['cart.sum'] = $_SESSION['cart.sum'] / $_SESSION['cart.currency']['value'] * $possibleCurrencies[$newCurrencyCode]['value'];
            }
            foreach ($_SESSION['cart'] as $k => $product){
                if ((int) $_SESSION['cart.currency']['base']){
                    $_SESSION['cart'][$k]['price'] *= $possibleCurrencies[$newCurrencyCode]['value'];
                }else{
                    //cast to the base type and multiply
                    $_SESSION['cart'][$k]['price'] = $_SESSION['cart'][$k]['price'] / $_SESSION['cart.currency']['value'] * $possibleCurrencies[$newCurrencyCode]['value'];
                }
            }
            foreach ($possibleCurrencies[$newCurrencyCode] as $k => $v){
                $_SESSION['cart.currency'][$k] = $v;
            }
        }
    }

}