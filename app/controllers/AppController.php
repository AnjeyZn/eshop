<?php

namespace app\controllers;


use app\models\AppModels;
use app\widgets\currency\Currency;
use ishop\App;
use ishop\base\Controller;

/**
 * Class AppController
 *
 * @package app\controllers
 */
class AppController extends Controller {

  /**
   * AppController constructor.
   *
   * @param $route
   */
  public function __construct($route) {
    parent::__construct($route);
    new AppModels();
    //setcookie('currency', 'EUR', time() + 3600*24*7, '/');
    // заносим список валют в контейнер Registry
    App::$app->setProperty('currencies', Currency::getCurrencies());
    // заносим в контейнер Registry активную валюту
    App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));

    //debug(App::$app->getProperties());
  }
}