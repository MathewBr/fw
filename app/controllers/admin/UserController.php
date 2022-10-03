<?php

namespace app\controllers\admin;

use app\models\User;
use fw\libs\Pagination;

class UserController extends AppFeature{

    public function indexAction(){
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 3;
        $count = \R::count('user');
        $pagination = new Pagination($page, $perpage, $count);

        $start = $pagination->startPosition();
        $users = \R::findAll('user', "LIMIT $start, $perpage");
        $this->setMeta('Список пользователей');
        $this->passData(compact('users', 'pagination', 'count'));
    }

    public function addAction(){
        $this->setMeta('Новый пользователь');
    }

    public function editAction(){
        if (!empty($_POST)){
            $id = $this->getRequestID(false);
            $user = new \app\models\admin\User();
            $form_data = $_POST;
            $user->selectiveLoading($form_data);
            if (!$user->attributes['password']){
                unset($user->attributes['password']);
            }else{
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
            }
            if (!$user->validate($form_data) || !$user->checkUnique()){
                $user->showValidageErors();
                redirect();
            }
            if ($user->update('user', $id)){
                $_SESSION['success'] = 'Изменения сохранены';
            }
            redirect();
        }

        $user_id = $this->getRequestID();
        $user = \R::load('user', $user_id);

        $orders = \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order` JOIN `order_product` ON `order`.`id` = `order_product`.`order_id` WHERE user_id = {$user_id} GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id`");

        $this->setMeta('Редактирование профиля пользователя');
        $this->passData(compact('user', 'orders'));
    }

    public function loginAdminAction(){
        if (!empty($_POST)){
            $user = new User();
            if ($user->login(true)){
                $_SESSION['success'] = 'Вы успешно авторизованы как админ';
            }else{
                $_SESSION['error'] = 'Логин/пароль для админа введены не верно';
            }
            if (User::isAdmin()){
                redirect(ADMIN);
            }else{
                redirect();
            }
        }
        $this->layout = 'login';
    }

}