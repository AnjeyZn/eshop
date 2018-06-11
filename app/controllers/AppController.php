<?php

namespace app\controllers;


use app\models\AppModels;
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
  }
}