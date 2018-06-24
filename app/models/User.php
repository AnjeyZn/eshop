<?php

namespace app\models;

/**
 * Class User
 *
 * @package app\models
 */
class User extends AppModels {

  /**
   * Данные поступающие из формы
   *
   * @var array
   */
  public $attributes = [
    'login'     => '',
    'password'  => '',
    'name'      => '',
    'email'     => '',
    'address'   => '',
  ];

  /**
   * Устанавливаем правила валидации
   *
   * @var array
   */
  public $rules = [
    'required' => [
      ['login'],
      ['password'],
      ['name'],
      ['email'],
      ['address'],
    ],
    'email' => [
      ['email'],
    ],
    'lengthMin' => [
      ['password', 6],
    ],
  ];

  /**
   * Проверка уникальности login and email
   *
   * @return bool
   */
  public function checkUnique() {
    $user = \R::findOne('user', 'login = ? OR email = ?', [$this->attributes['login'], $this->attributes['email']]);

    if ($user) {
      if ($user->login == $this->attributes['login']) {
        $this->errors['unique'][] = 'Этот логин уже используется!';
      }
      if ($user->email == $this->attributes['email']) {
        $this->errors['unique'][] = 'Этот email уже используется!';
      }
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Получаем список ошибок валидации
   */
  public function getErrors() {
    $errors = '<ul>';
      foreach ($this->errors as $error) {
        foreach ($error as $item) {
          $errors .= "<li>$item</li>";
        }
      }
    $errors .= '</ul>';
      $_SESSION['error'] = $errors;
  }
}