<?php

namespace app\models;

/**
 * Class Product
 *
 * @package app\models
 */
class Product extends AppModels {

  /**
   * Запись просмотреных товаров в куки
   *
   * @param $id
   */
  public function setRecentlyView($id) {
    $recentlyViewed = $this->getAllRecentlyView();

    if (!$recentlyViewed) {
      setcookie('recentlyViewed', $id, time() + 3600*24, '/');
    } else {
      $recentlyViewed = explode('.', $recentlyViewed);
      if (!in_array($id, $recentlyViewed)) {
        $recentlyViewed[] = $id;
        $recentlyViewed = implode('.', $recentlyViewed);
        setcookie('recentlyViewed', $recentlyViewed, time() + 3600*24, '/');
      }
    }
  }

  /**
   * Получаем три последних просмотреных товара
   *
   * @return array
   */
  public function getRecentlyView() {
    if (!empty($_COOKIE['recentlyViewed'])) {
      $recentlyViewed = $_COOKIE['recentlyViewed'];
      $recentlyViewed = explode('.', $recentlyViewed);
      return array_slice($recentlyViewed, -3);
    }
  }

  /**
   * Получаем все просмотренные товары
   *
   * @return mixed
   */
  public function getAllRecentlyView() {
    if (!empty($_COOKIE['recentlyViewed'])) {
      return $_COOKIE['recentlyViewed'];
    }
  }
}