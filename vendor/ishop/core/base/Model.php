<?php

namespace ishop\base;

use ishop\Db;

/**
 * Class Model
 *
 * @package ishop\base
 */
abstract class Model {

  /**
   * Массив свойств модели
   *
   * @var array
   */
  public $attributes = [];

  /**
   * Массив ошибок
   *
   * @var array
   */
  public $errors = [];

  /**
   * Массив правил валидации
   *
   * @var array
   */
  public $rules = [];

  /**
   * Model constructor
   */
  public function __construct() {
    Db::instance();
  }

  public function load($data) {
    foreach ($this->attributes as $key => $value) {
      if (isset($data[$key])) {
        $this->attributes[$key] = $data[$key];
      }
    }
  }
}