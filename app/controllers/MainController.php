<?php

namespace app\controllers;


/**
 * Class MainController
 *
 * @package app\controllers
 */
class MainController extends AppController {

  public function indexAction() {

    $brands = \R::find('brand', 'LIMIT 3');
    $hits = \R::find('product', "hit = '1' AND status = '1' LIMIT 8");

    $this->setMeta('Главная страница','Описание','Ключи');
    $this->set(compact('brands', 'hits'));
  }
}