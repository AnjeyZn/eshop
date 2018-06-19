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