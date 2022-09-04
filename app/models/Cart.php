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

}