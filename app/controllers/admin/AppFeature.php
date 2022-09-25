<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\User;
use fw\base\Controller;

class AppFeature extends Controller{
    public $layout = 'admin';

    public function __construct($route){
        parent::__construct($route);

        if (!User::isAdmin() && $route['action'] != 'login-admin'){ //second condition to avoid looping
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }
        new AppModel();//so that you can call the class /R in descendants
    }
}