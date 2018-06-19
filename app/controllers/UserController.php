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

      if (!$user->validate($data)) {
        $user->getErrors();
        redirect();
      } else {
        $_SESSION['success'] = 'Ok!';
        redirect();
      }
    }

    $this->setMeta('Регистрация');
  }

  public function loginAction() {}

  public function logoutAction() {}

}