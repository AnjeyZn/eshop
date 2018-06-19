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
}