<?php

namespace app\controllers;

/**
 * Class SearchController
 *
 * @package app\controllers
 */
class SearchController extends AppController{

  /**
   * Асинхронная выборка данных из БД для строки поиска
   */
  public function typeaheadAction(){
    if($this->isAjax()){
      $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : NULL;
      if($query){
        $products = \R::getAll('SELECT id, title FROM product WHERE title LIKE ? LIMIT 11', ["%{$query}%"]);
        echo json_encode($products);
      }
    }
    die;
  }

  /**
   * Выборка продукта из БД по переданному запросу
   */
  public function indexAction() {
    $query = !empty(trim($_GET['s'])) ? trim($_GET['s']) : NULL;

    if ($query) {
      $products = \R::find('product', "title LIKE ?", ["%{$query}%"]);
    }
    $this->setMeta('Поиск по: ', h($query));
    $this->set(compact('products', 'query'));
  }

}