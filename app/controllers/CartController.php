<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\User;

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

    public function showAction(){
        $this->sendAjaxResponse('cart_modal');
    }

    public function deleteAction(){
        $id = !empty($_GET['id']) ? $_GET['id'] : null;//id may be '1' or '1-2'
        if (isset($_SESSION['cart'][$id])){
            $cart = new Cart();
            $cart->deleteItem($id);
        }
        if ($this->isAjax()){
            $this->sendAjaxResponse('cart_modal'); //variables are available globally in the session because the cart is stored in the session
        }
        redirect();
    }

    public function clearAction(){
        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.sum']);
        unset($_SESSION['cart.currency']);
        if ($this->isAjax()){
            $this->sendAjaxResponse('cart_modal'); //variables are available globally in the session because the cart is stored in the session
        }
        redirect();
    }

    public function purchaseAction(){
        $this->setMeta('Корзина');
    }

    public function ordergoodsAction(){
        if (!empty($_POST)){
            //user registration
            if(!User::isAuthorized()){
                $user = new User();
                $order = $_POST;
                $user->selectiveLoading($order);

                if (!$user->validate($order) || !$user->checkUnique()){
                    $user->showValidageErors();
                    $_SESSION['form-data'] = $order;
                    redirect(); //if there is an error, the order is not processed
                }else{
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                    if (!$idUser = $user->saveInDbase('user')){
                        $_SESSION['errors'] = 'Ошибка записи в базу данных. Запись не добавлена.';
                        redirect(); //if there is an error, the order is not processed
                    }
                }
            }

            //save order
            //if the user is not authorized, add in $order the result of the saveInDbase('user')
            //if the user is authorized, add from session
            $order['user_id'] = isset($idUser) ? $idUser : $_SESSION['user']['id'];
            $order['note'] = !empty($_POST['note']) ? trim($_POST['note']) : '';
            $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
            $order_id = Order::saveOrder($order);
            Order::mailOrder($order_id,$user_email);
        }
        redirect();
    }

}