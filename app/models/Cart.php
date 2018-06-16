<?php

namespace app\models;


use ishop\App;

/**
 * Class Cart
 *
 * @package app\models
 */
class Cart extends AppModels {

  /**
   * Добавление товара в корзину
   *
   * @param      $product
   * @param int  $qty (к-во товара)
   * @param null $mod (модификация товара)
   */
  public function addToCart($product, $qty = 1, $mod = NULL) {

    // проверяем есть ли в сессии активная валюта
    if (!isset($_SESSION['cart.currency'])) {
      $_SESSION['cart.currency'] = App::$app->getProperty('currency');
    }

    // проверяем выбран базовый товар или его модификация
    if ($mod) {
      $ID = "{$product->id}-{$mod->id}";  // [5]-[1]
      $title = "{$product->title} ({$mod->title})";
      $price = $mod->price;
    } else {
      $ID = $product->id;
      $title = $product->title;
      $price = $product->price;
    }

    // проверяем есть ли такой товар уже в корзине, если есть
    // добавляем количество (qty)
    if (isset($_SESSION['cart'][$ID])) {
      $_SESSION['cart'][$ID]['qty'] += $qty;
    } else {
      $_SESSION['cart'][$ID] = [
        'qty' => $qty,
        'title' => $title,
        'alias' => $product->alias,
        'price' => $price * $_SESSION['cart.currency']['value'],
        'img' => $product->img,
      ];
    }

    // подсчитываем к-во товара и общую сумму
    $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
    $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * ($price * $_SESSION['cart.currency']['value']) : $qty * ($price * $_SESSION['cart.currency']['value']);
  }

  /**
   * Удаление конкретного товара из корзины
   *
   * @param $id
   */
  public function deleteItem($id) {
    $qtyMinus = $_SESSION['cart'][$id]['qty'];
    $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];

    $_SESSION['cart.qty'] -= $qtyMinus;
    $_SESSION['cart.sum'] -= $sumMinus;

    unset($_SESSION['cart'][$id]);
  }

  /**
   * Пересчет валюты
   *
   * @param $curr
   */
  public static function recalc($curr) {
    if (isset($_SESSION['cart.currency'])) {
      if ($_SESSION['cart.currency']['base']) {
        $_SESSION['cart.sum'] *= $curr->value;
      } else {
        $_SESSION['cart.sum'] = $_SESSION['cart.sum'] / $_SESSION['cart.currency']['value'] * $curr->value;
      }

      // Пересчет валюты
      foreach ($_SESSION['cart'] as $key => $value) {
        if ($_SESSION['cart.currency']['base']) {
          $_SESSION['cart'][$key]['price'] *= $curr->value;
        } else {
          $_SESSION['cart'][$key]['price'] = $_SESSION['cart'][$key]['price'] / $_SESSION['cart.currency']['value'] * $curr->value;
        }
      }

      // перезаписываем активную валюту в сессию
      foreach ($curr as $k => $v) {
        $_SESSION['cart.currency'][$k] = $v;
      }
    }
  }

}