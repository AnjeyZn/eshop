<?php

namespace ishop;

/**
 * Class Registry
 *
 * @package ishop
 */
class Registry {

  use TSingletone;

  /**
   * Контейнер для параметров
   *
   * @var array
   */
  protected static $properties = [];

  /**
   * Записать значение по указаному ключу
   *
   * @param $name
   * @param $value
   */
  public function setProperty($name, $value) {
    self::$properties[$name] = $value;
  }

  /**
   * Получить данные по ключу
   *
   * @param $name
   *
   * @return mixed|null
   */
  public function getProperty($name) {
    if (isset(self::$properties[$name])) {
      return self::$properties[$name];
    }
    return NULL;
  }

  /**
   * Просмотр всего контейнера
   *
   * @return array
   */
  public function getProperties() {
    return self::$properties;
  }

}