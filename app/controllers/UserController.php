<?php

namespace app\controllers;

use app\models\Two;
use app\models\User;

class UserController extends AppFeature{

    public function signupAction(){
        if (!empty($_POST)){
            $user = new User();
            $post = $_POST;
            $user->selectiveLoading($post);

            if (!$user->validate($post) || !$user->checkUnique()){
                $user->showValidageErors();
                $_SESSION['form-data'] = $post;
            }else{
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                if ($idUser = $user->saveInDbase('user')){
                    $_SESSION['success'] = 'Пользователь зарегистрирован.';
                }else{
                    $_SESSION['errors'] = 'Ошибка записи в базу данных. Запись не добавлена.';
                }
            }
            redirect();
        }
        $this->setMeta('Регистрация');
    }

    public function loginAction(){
        if (!empty($_POST)){
            $user = new User();
            if ($user->login()){
                $_SESSION['success'] = 'Вы успешно авторизованы.';
            }else{
                $_SESSION['errors'] = 'Логин/пароль введены не верно';
            }
            redirect();
        }
        $this->setMeta('Вход');
    }

    public function logoutAction(){
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        redirect();
    }

}