<?php

namespace app\controllers\admin;


use app\models\User;

/**
 * Class UserController
 *
 * @package app\controllers\admin
 */
class UserController extends AppController
{

    /**
     * Авторизация администратора
     */
    public function loginAdminAction() {

        if (!empty($_POST)) {
            $user = new User();

            if ($user->login(true)) {
                $_SESSION['success'] = 'Вы успешно авторизованы!';
            } else {
                $_SESSION['error'] = 'Логин/пароль введены не верно!';
            }

            if (User::isAdmin()) {
                redirect(ADMIN);
            } else {
                redirect();
            }
        }

        $this->layout = 'login';
    }
}