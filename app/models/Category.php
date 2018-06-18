<?php

namespace app\models;


use ishop\App;

/**
 * Class Category
 *
 * @package app\models
 */
class Category extends AppModels {

  /**
   * Получение id товаров для данной категории
   *
   * @param $id
   *
   * @return null|string
   */
  public function getIds($id) {
    $cats = App::$app->getProperty('cats');

    $ids = NULL; // список id доступных товаров для данной категории
    foreach ($cats as $key => $value) {
      if ($value['parent_id'] == $id) {
        $ids .= $key . ',';
        $ids .= $this->getIds($key);
      }
    }
    return $ids;
  }

}