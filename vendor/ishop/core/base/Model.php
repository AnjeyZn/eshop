<?php

namespace ishop\base;

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

  }
}