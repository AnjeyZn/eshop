<?php

namespace app\models;

/**
 * Class Order
 *
 * @package app\models
 */
class Order extends AppModels {

  /**
   * Сохранение заказа
   *
   * @param $data
   *
   * @return int
   */
  public static function saveOrder($data) {
    // создаем объект ActiveRecord
    $order = \R::dispense('order');
    // заполняем его данными
    $order->user_id = $data['user_id'];
    $order->note = $data['note'];
    $order->currency = $_SESSION['cart.currency']['code'];
    $order_id = \R::store($order);
    self::saveOrderProduct($order_id);
    return $order_id;
  }

  /**
   * Сохранение продуктов для заказа с номером $order_id
   * в таблицу order_product
   *
   * @param $order_id
   */
  public static function saveOrderProduct($order_id) {
    $sql_part = '';
    foreach ($_SESSION['cart'] as $product_id => $product) {
      $product_id = (int) $product_id;
      $sql_part .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
    }
    $sql_part = rtrim($sql_part, ',');
    \R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
  }

  /**
   * Отправка сообщения
   *
   * @param $order_id
   * @param $user_email
   */
  public static function mailOrder($order_id, $user_email) {}
}