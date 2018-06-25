<?php

namespace app\models;

use ishop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

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
  public static function mailOrder($order_id, $user_email){
    // Create the Transport
    $transport = (new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), App::$app->getProperty('smtp_protocol')))
      ->setUsername(App::$app->getProperty('smtp_login'))
      ->setPassword(App::$app->getProperty('smtp_password'))
    ;
    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    // Create a message
    ob_start();
    require APP . '/views/mail/mail_order.php';
    $body = ob_get_clean();

    $message_client = (new Swift_Message("Вы совершили заказ №{$order_id} на сайте " . App::$app->getProperty('shop_name')))
      ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
      ->setTo($user_email)
      ->setBody($body, 'text/html')
    ;

    $message_admin = (new Swift_Message("Сделан заказ №{$order_id}"))
      ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
      ->setTo(App::$app->getProperty('admin_email'))
      ->setBody($body, 'text/html')
    ;

    // Send the message
    $result = $mailer->send($message_client);
    $result = $mailer->send($message_admin);
    unset($_SESSION['cart']);
    unset($_SESSION['cart.qty']);
    unset($_SESSION['cart.sum']);
    unset($_SESSION['cart.currency']);
    $_SESSION['success'] = 'Спасибо за Ваш заказ. В ближайшее время с Вами свяжется менеджер для согласования заказа';
  }
}