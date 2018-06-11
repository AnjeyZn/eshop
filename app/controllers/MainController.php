<?php

namespace app\controllers;


/**
 * Class MainController
 *
 * @package app\controllers
 */
class MainController extends AppController {

  public function indexAction() {

    $this->setMeta('Главная страница','Описание','Ключи');
  }
}