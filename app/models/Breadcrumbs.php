<?php

namespace app\models;


use ishop\App;

/**
 * Class Breadcrumbs
 *
 * @package app\models
 */
class Breadcrumbs {

  /**
   * Формируем хлебные крошки
   *
   * @param        $category_id
   * @param string $name
   *
   * @return string
   */
  public static function getBreadcrumbs($category_id, $name = '') {
    $cats = App::$app->getProperty('cats');
    $breadcrumbs_array = self::getParts($cats, $category_id);
    $breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>";
    if ($breadcrumbs_array) {
      foreach ($breadcrumbs_array as $alias => $title) {
        $breadcrumbs .= "<li><a href='" . PATH . "/category/{$alias}'>{$title}</a></li>";
      }
    }

    if ($name) {
      $breadcrumbs .= "<li>$name</li>";
    }
    return $breadcrumbs;
  }

  /**
   * Ищем цепочку хлебных крошек
   *
   * @param $cats
   * @param $id
   *
   * @return array
   */
  public static function getParts($cats, $id) {
    if (!$id) return FALSE;

    $breadcrumbs = [];
    foreach ($cats as $key => $value) {
      if (isset($cats[$id])) {
        $breadcrumbs[$cats[$id]['alias']] = $cats[$id]['title'];
        $id = $cats[$id]['parent_id'];
      } else break;
    }
    return array_reverse($breadcrumbs, TRUE);
  }
}