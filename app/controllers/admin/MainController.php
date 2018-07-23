<?php

namespace app\controllers\admin;


class MainController extends AppController
{

    public function indexAction() {
        // выводим кол-во необработанных заказов (табл: order, status: 0)
        $countNewOrders = \R::count('order', "status = '0'");

        // кол-во зарегистрированных пользователей пользователей
        $countUsers = \R::count('user');

        // кол-во продуктов
        $countProducts = \R::count('product');

        // категории
        $countCategories = \R::count('category');

        $this->setMeta('Панель управления');
        $this->set(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers'));
    }
}