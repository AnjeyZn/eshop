<?php

namespace app\controllers;


class ProductController extends AppController {

  public function viewAction() {
    $alias = $this->route['alias'];
    $product = \R::findOne('product', "alias=? AND status='1'", [$alias]);

    if (!$product) {
      throw new \Exception('Страница не найдена', 404);
      //TODO Залогировать отсутствующую страницу
    }
    //TODO Получить хлебные крошки
    //TODO Получить связаные товары
    //TODO Запись в куки просмотренный товар
    //TODO Получить все просмотренные товары из кук
    //TODO Получить галерею
    //TODO Получить модификацию товара

    $this->setMeta($product->title, $product->description, $product->keywords);
    $this->set(compact('product'));
  }

}