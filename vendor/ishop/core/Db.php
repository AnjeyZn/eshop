<?php

namespace ishop;

use R;

/**
 * Class Db
 *
 * @package ishop
 */
class Db {

  use TSingletone;

  /**
   * Db constructor.
   *
   * @throws \Exception
   */
  protected function __construct() {
    $db = require_once CONF . '/config_db.php';
    require LIBS . '/rb.php';

    R::setup($db['dsn'], $db['user'], $db['pass']);
    // Откл создание таблиц и полей на лету
    R::freeze(TRUE);

    if (DEBUG) {
      // Вкл режим отладки (показ количества запросов и времени)
      R::debug(TRUE, 1);
    }

    if (!R::testConnection()) {
      throw new \Exception("Нет соединения с БД", 500);
    }
  }

}