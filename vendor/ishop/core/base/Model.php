<?php

namespace ishop\base;

use ishop\Db;
use Valitron\Validator;

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

  /**
   * Автоматическая загрузка атрибутов
   *
   * @param $data
   */
  public function load($data) {
    foreach ($this->attributes as $name => $value) {
      if (isset($data[$name])) {
        $this->attributes[$name] = $data[$name];
      }
    }
  }

  /**
   * Сохраняем данные из формы
   *
   * @param $table
   *
   * @return int
   */
  public function save($table) {

    $tbl = \R::dispense($table);

    foreach ($this->attributes as $key => $value) {
      $tbl->$key = $value;
    }

    return \R::store($tbl);
  }

  /**
   * Валидация данных
   *
   * @param $data
   *
   * @return bool
   */
  public function validate($data) {
    Validator::langDir(WWW . '/validator/lang');
    Validator::lang('ru');
    $v = new Validator($data);
    $v->rules($this->rules);

    if ($v->validate()) {
      return TRUE;
    } else {
      $this->errors = $v->errors();
      return FALSE;
    }
  }
}