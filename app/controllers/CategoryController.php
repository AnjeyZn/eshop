<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use ishop\App;
use ishop\libs\Pagination;

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

    $cat_model = new Category();
    // получаем всех потомков данной категории
    $ids = $cat_model->getIds($category->id);
    // если категория не имеет потомков то выводим ее id
    $ids = !$ids ? $category->id : $ids . $category->id;

    // Pagination
    // текущая страница
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    // к-во выводимого товара на страницу
    $perpage = App::$app->getProperty('pagination');

    // запрос по фильтру
    if ($this->isAjax()) {
      debug($_GET);
      die;
    }

    // к-во записей в БД
    $total = \R::count('product', "category_id IN ($ids)");

    $pagination = new Pagination($page, $perpage, $total);
    // с какой страницы стартуем
    $start = $pagination->getStart();

    // хлебные крошки
    $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);

    $products = \R::find('product', "category_id IN ($ids) LIMIT $start, $perpage");

    $this->setMeta($category->title, $category->description, $category->keywords);
    $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
  }
}