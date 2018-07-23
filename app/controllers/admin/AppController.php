<?php

namespace app\controllers\admin;


use app\models\AppModels;
use app\models\User;
use ishop\base\Controller;

class AppController extends Controller
{
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);

        if (!User::isAdmin() && $route['action'] != 'login-admin') {
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }

        new AppModels();
    }
}