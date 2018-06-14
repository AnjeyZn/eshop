<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Product;

/**
 * Class ProductController
 *
 * @package app\controllers
 */
class ProductController extends AppController {

  public function viewAction() {
    $alias = $this->route['alias'];
    $product = \R::findOne('product', "alias=? AND status='1'", [$alias]);

    if (!$product) {
      throw new \Exception('Страница не найдена', 404);
      //TODO Залогировать отсутствующую страницу
    }
    //Получить хлебные крошки
    $breadcrumbs = Breadcrumbs::getBreadcrumbs($product->category_id, $product->title);

    //Получить связаные товары
    $related = \R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);


    //Записываем в куки просмотренный товар
    $p_model = new Product();
    $p_model->setRecentlyView($product->id);

    //Получить все просмотренные товары из кук
    $r_viewed = $p_model->getRecentlyView();
    $recentlyViewed = NULL;

    if ($r_viewed) {
      $recentlyViewed = \R::find('product', 'id IN (' . \R::genSlots($r_viewed) . ') LIMIT 3', $r_viewed);
    }

    //Получить галерею
    $gallery = \R::findAll('gallery', 'product_id=?', [$product->id]);


    //TODO Получить модификацию товара

    $this->setMeta($product->title, $product->description, $product->keywords);
    $this->set(compact('product', 'related', 'gallery', 'recentlyViewed', 'breadcrumbs'));
  }

}