<?php

namespace app\controllers;

use app\models\Cart;

class CartController extends AppFeature {

    public function addAction(){
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $quantity = !empty($_GET['qty']) ? (int)$_GET['qty'] : null;
        $modification_id = !empty($_GET['mod']) ? (int)$_GET['mod'] : null;
        $product = null;
        $modification = null;

        if ($id){
            $product = \R::findOne('product', 'id = ?', [$id]);
            if (!$product) return false;
            if ($modification_id){
                $modification = \R::findOne('modification', 'id = ? AND product_id = ?', [$modification_id, $id]);
            }
        }

        $cart = new Cart();
        $cart->addToCart($product,$quantity,$modification);
        if ($this->isAjax()){
            $this->sendAjaxResponse('cart_modal'); //variables are available globally in the session because the cart is stored in the session
        }
        redirect();

    }

}