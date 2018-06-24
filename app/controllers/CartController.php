<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\User;

/**
 * Class CartController
 *
 * @package app\controllers
 */
class CartController extends AppController {

  /**
   * Добавить товар в корзину
   *
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

    // если Ajax запрос - выводим модальное окно
    if ($this->isAjax()) {
      $this->loadView('cart_modal');
    }

    redirect();
  }

  /**
   * Показать модальное окно
   */
  public function showAction(){
    $this->loadView('cart_modal');
  }

  public function deleteAction(){
    $id = !empty($_GET['id']) ? $_GET['id'] : null;
    if(isset($_SESSION['cart'][$id])){
      $cart = new Cart();
      $cart->deleteItem($id);
    }
    if($this->isAjax()){
      $this->loadView('cart_modal');
    }
    redirect();
  }

  /**
   * Очистить корзину
   */
  public function clearAction() {
    unset($_SESSION['cart']);
    unset($_SESSION['cart.qty']);
    unset($_SESSION['cart.sum']);
    unset($_SESSION['cart.currency']);

    if($this->isAjax()){
      $this->loadView('cart_modal');
    }
    redirect();
  }

  public function viewAction() {


    $this->setMeta('Оформление заказа');
  }

  /**
   * Оформляем заказ
   */
  public function checkoutAction() {
    if (!empty($_POST)) {
      // если пользователь не зарегистрирован
      if (!User::checkAuth()) {
        $user = new User();
        $data = $_POST;
        $user->load($data);

        if (!$user->validate($data) || !$user->checkUnique()) {
          $user->getErrors();
          $_SESSION['form_data'] = $data;
          redirect();
        } else {
          $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
          if (!$user_id = $user->save('user')) {
            $_SESSION['error'] = 'Ошибка регистрации!';
            redirect();
          }
        }
      }

      // сохранение заказа
      $data['user_id'] = isset($user_id) ? $user_id : $_SESSION['user']['id'];
      $data['note'] = !empty($_POST['note']) ? $_POST['note'] : '';
      $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
      $order_id = Order::saveOrder($data);
      Order::mailOrder($order_id, $user_email);
    }
    redirect();
  }
}