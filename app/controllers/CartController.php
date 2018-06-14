<?php

namespace app\controllers;

use app\models\Cart;

/**
 * Class CartController
 *
 * @package app\controllers
 */
class CartController extends AppController {

  /**
   * @return bool
   */
  public function addAction() {
    $id = !empty($_GET['id']) ? (int)$_GET['id'] : NULL;
    $qty = !empty($_GET['qty']) ? (int)$_GET['qty'] : NULL;
    $mod_id = !empty($_GET['mod']) ? (int)$_GET['mod'] : NULL;
    $mod = NULL;

    if ($id) {
      $product = \R::findOne('product', 'id = ?', [$id]);

      if (!$product) {
        return FALSE;
      }

      if ($mod_id) {
        $mod = \R::findOne('modification', 'id = ? AND product_id = ?', [$mod_id, $id]);
      }
    }
    $cart = new Cart();
    $cart->addToCart($product, $qty, $mod);

    if ($this->isAjax()) {
      $this->loadView('cart_modal');
    }

    redirect();
  }
}