<?php

namespace app\controllers;

use app\models\User;

/**
 * Class UserController
 *
 * @package app\controllers
 */
class UserController extends AppController {

  /**
   * Registration
   */
  public function signupAction() {
    if (!empty($_POST)) {
      $user = new User();
      $data = $_POST;
      $user->load($data);

      if (!$user->validate($data) || !$user->checkUnique()) {
        $user->getErrors();
        $_SESSION['form_data'] = $data;
      } else {
        $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
        if ($user->save('user')) {
          $_SESSION['success'] = 'Регистрация прошла успешно!';
        } else {
          $_SESSION['error'] = 'Ошибка регистрации!';
        }
      }
      redirect();
    }

    $this->setMeta('Регистрация');
  }

  /**
   * Авторизация пользователя
   */
  public function loginAction() {
    if (!empty($_POST)) {
      $user = new User();

      if ($user->login()) {
        $_SESSION['success'] = 'Вы успешно авторизованы!';
      } else {
        $_SESSION['success'] = 'Логин/пароль введены не верно';
      }
      redirect();
    }

    $this->setMeta('Вход');
  }

  /**
   *
   */
  public function logoutAction() {
    if (isset($_SESSION['user'])) unset($_SESSION['user']);
    redirect();
  }

}