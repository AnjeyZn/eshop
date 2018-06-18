<?php

namespace app\controllers;

use app\models\Category;

/**
 * Class CategoryController
 *
 * @package app\controllers
 */
class CategoryController extends AppController {

  /**
   * Получение товаров категорий
   *
   * @throws \Exception
   * @return void
   */
  public function viewAction() {
    $alias = $this->route['alias'];
    $category = \R::findOne('category', 'alias = ?', [$alias]);

    if (!$category) {
      throw new \Exception('Страница не найдена', 404);
    }

    // TODO хлебные крошки
    $breadcrumbs = '';

    $cat_model = new Category();
    // получаем всех потомков данной категории
    $ids = $cat_model->getIds($category->id);
    // если категория не имеет потомков то выводим ее id
    $ids = !$ids ? $category->id : $ids . $category->id;

    $products = \R::find('product', "category_id IN ($ids)");

    $this->setMeta($category->title, $category->description, $category->keywords);
    $this->set(compact('products', 'breadcrumbs'));
  }
}