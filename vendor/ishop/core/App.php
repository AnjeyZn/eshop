<?php

namespace ishop;

/**
 * Class App
 *
 * @package ishop
 */
class App {

  /**
   * Объект приложения
   */
  public static $app;

  /**
   * App constructor
   */
  public function __construct() {
    $query = trim($_SERVER['QUERY_STRING'], '/');
    session_start();
    self::$app = Registry::instance();
    $this->getParams();
    new ErrorHandler();
    Router::dispatch($query);
  }

  /**
   * Запись параметров из конфигурационного файла в контейнер
   */
  protected function getParams() {
    $params = require_once CONF . '/params.php';
    if (!empty($params)) {
      foreach ($params as $k => $v) {
        self::$app->setProperty($k, $v);
      }
    }
  }

}