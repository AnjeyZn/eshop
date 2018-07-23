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

    /**
     * Получение Id параметра из запроса
     *
     * @param bool   $get
     * @param string $id
     *
     * @return int|null|string
     * @throws \Exception
     */
    public function getRequestId($get = true, $id = 'id') {
        if ($get) {
            $data = $_GET;
        } else {
            $data = $_POST;
        }

        $id = !empty($data[$id]) ? (int) $data[$id] : null;

        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        return $id;
    }
}