<?php

namespace app\controllers\admin;

use fw\libs\Pagination;

class OrderController extends AppFeature{

    public function indexAction(){
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 3;
        $count = \R::count('order');
        $pagination = new Pagination($page, $perpage, $count);
        $start = $pagination->startPosition();

        $orders = \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT $start, $perpage");

        $this->setMeta('Список заказов');
        $this->passData(compact('orders', 'pagination', 'count'));
    }

    public function viewAction(){
        $order_id = $this->getRequestID();
        $order = \R::getRow("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE `order`.`id` = ?
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1", [$order_id]);
        if (!$order){
            throw new \Exception('Заказ не найден', 404);
        }
        $order_products = \R::findAll('order_product', "order_id = ?", [$order_id]);

        $this->setMeta("Заказ № {$order_id}");
        $this->passData(compact('order', 'order_products'));
    }

    public function changeAction(){
        $order_id = $this->getRequestID();
        $status = !empty($_GET['status']) ? 1 : 0;//in database the status field is a string
        if (!$order_id){
            throw new \Exception('ID заказа не передан', 404);
        }
        $order = \R::load('order', $order_id);
        if (!$order){
            throw new \Exception('Заказ не найден в базе данных', 404);
        }
        $order->status = $status;
        $order->update_at = date("Y-m-d H:i:s");
        if (\R::store($order)){
            $_SESSION['success'] = 'Изменения сохранены';
        }else{
            $_SESSION['errors'] = 'Не удалось сохранить измененмя';
        }
        redirect();
    }

    public function deleteAction(){
        $order_id = $this->getRequestID();
        if (!$order_id){
            throw new \Exception('ID заказа не передан', 404);
        }
        $order = \R::load('order', $order_id);
        if (!$order){
            throw new \Exception('Заказ не найден в базе данных', 404);
        }
        \R::trash($order);
        $_SESSION['success'] = 'Заказ удален';
        redirect(ADMIN . '/order');
    }

}