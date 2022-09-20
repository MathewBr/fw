<?php

namespace app\models;

use fw\App;

class Order extends AppModel {

    public static function saveOrder($order){
        $order_record = \R::dispense('order');
        $order_record->user_id = $order['user_id'];
        $order_record->note = $order['note'];
        $order_record->currency = $_SESSION['cart.currency']['code'];
        $order_record_id = \R::store($order_record);
        self::saveOrderProduct($order_record_id);
        return $order_record_id;
    }

    public static function saveOrderProduct($order_id){
        $sql = '';
        foreach ($_SESSION['cart'] as $product_id => $product){
            $product_id = (int)$product_id; // 1-3 will turn into 1 if the item is modified
            $sql .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sql = rtrim($sql, ',');
        \R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql");
    }

    public static function mailOrder($order_id, $user_email){
        $transport = (new \Swift_SmtpTransport(App::$appContainer->getParameter('smtp_host'), App::$appContainer->getParameter('smtp_port'), App::$appContainer->getParameter('smtp_protocol')))
        ->setUsername(App::$appContainer->getParameter('smtp_login'))
        ->setPassword(App::$appContainer->getParameter('smtp_password'));

        $mailer = new \Swift_Mailer($transport);

        ob_start();
        require APP . '/views/mail/mail_order.php';
        $body = ob_get_clean();

        $message_client = (new \Swift_Message("Заказ №{$order_id}"))
        ->setFrom([App::$appContainer->getParameter('smtp_login') => App::$appContainer->getParameter('shop_name')])
        ->setTo($user_email)
        ->setBody($body, 'text/html');

        $message_admin = (new \Swift_Message("Сообщение для менеджера №{$order_id}"))
            ->setFrom([App::$appContainer->getParameter('smtp_login') => App::$appContainer->getParameter('shop_name')])
            ->setTo(App::$appContainer->getParameter('admin_email'))
            ->setBody($body, 'text/html');

        $result = $mailer->send($message_client);
        $result = $mailer->send($message_admin);

        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.sum']);
        unset($_SESSION['cart.currency']);
        $_SESSION['success'] = 'Спасибо за заказ.';
    }

}