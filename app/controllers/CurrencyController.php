<?php

namespace app\controllers;


use app\models\Cart;

/**
 * Class CurrencyController
 *
 * @package app\controllers
 */
class CurrencyController extends AppController {

  /**
   * Изменение активной валюты
   */
  public function changeAction() {
    $currency = !empty($_GET['curr']) ? $_GET['curr'] : NULL;
    if ($currency) {
      $curr = \R::findOne('currency', 'code = ?', [$currency]);

      if (!empty($curr)) {
        setcookie('currency', $currency, time() + 3600*24*7, '/');
        Cart::recalc($curr);
      }
    }
    redirect();
  }

}