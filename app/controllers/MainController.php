<?php

namespace app\controllers;


use ishop\App;
use ishop\Cache;
use R;

/**
 * Class MainController
 *
 * @package app\controllers
 */
class MainController extends AppController {

  public function indexAction() {
    $this->layout = 'main';

    $this->setMeta(App::$app->getProperty('shop_name'), 'Описание страницы', 'Ключи');

    $myname = 'Andrey';
    $age = '39';


    $names = R::findAll('users');

    $cache = Cache::instance();

    $data = $cache->get('names');
    //$cache->delete('names');

    if (!$data) {
      $cache->set('names', $names);
    }

    $name = R::findOne('users', 'id = ?', [2]);

    $this->set(compact('myname', 'age', 'names'));
  }
}