<?php

namespace app\controllers;


use ishop\App;

class MainController extends AppController {

  public function indexAction() {
    $this->layout = 'main';

    $this->setMeta(App::$app->getProperty('shop_name'), 'Описание страницы', 'Ключи');

    $name = 'Andrey';
    $age = '39';
    $this->set(compact('name', 'age'));
  }
}