<?php

namespace app\controllers\admin;


use app\models\AppModels;
use app\models\User;
use ishop\base\Controller;

/**
 * Class AppController
 *
 * Ограничиваем доступ к админской части
 *
 * @package app\controllers\admin
 */
class AppController extends Controller
{

    /**
     * @var string
     */
    public $layout = 'admin';

    /**
     * AppController constructor.
     *
     * @param $route
     */
    public function __construct($route)
    {
        parent::__construct($route);

        if (!User::isAdmin() && $route['action'] != 'login-admin') {
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }

        new AppModels();
    }
}