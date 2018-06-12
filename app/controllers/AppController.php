<?php

namespace app\controllers;


use app\models\AppModels;
use app\widgets\currency\Currency;
use ishop\App;
use ishop\base\Controller;
use ishop\Cache;

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
    // заносим список валют в контейнер Registry
    App::$app->setProperty('currencies', Currency::getCurrencies());
    // заносим в контейнер Registry активную валюту
    App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
    // заносим в контейнер Registry кэш категорий
    App::$app->setProperty('cats', self::cacheCategory());

    //debug(App::$app->getProperties());
  }

  /**
   * Кэшируем массив категорий
   *
   * @return array
   */
  public static function cacheCategory() {
    $cache = Cache::instance();
    $cats = $cache->get('cats');

    if (!$cats) {
      $cats = \R::getAssoc("SELECT * FROM category");
      $cache->set('cats', $cats);
    }
    return $cats;
  }
}