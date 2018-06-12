<?php

namespace app\widgets\menu;

use ishop\App;
use ishop\Cache;

/**
 * Class Menu
 *
 * @package app\widgets\menu
 */
class Menu {

  /**
   * Данные для строительства дерева меню
   *
   * @var array
   */
  protected $data;

  /**
   * Массив дерева
   *
   * @var array
   */
  protected $tree;

  /**
   * Готовый код HTML меню
   *
   * @var
   */
  protected $menuHtml;

  /**
   * Шаблон для вывода меню
   *
   * @var
   */
  protected $tpl;

  /**
   * Контейнер для меню (ul, select ...)
   *
   * @var string
   */
  protected $container = 'ul';

  /**
   * Название класса меню контейнера
   * <ul class="menu"></ul>
   *
   * @var string
   */
  protected $class = 'menu';

  /**
   * Таблица выборки данных
   *
   * @var string
   */
  protected $table = 'category';

  /**
   * На какое время кэшируем меню
   *
   * @var int
   */
  protected $cache = 3600;

  /**
   * Ключ под которым сохраняется кэш файл
   *
   * @var string
   */
  protected $cacheKey = 'ishop_menu';

  /**
   * Массив дополнительных аттрибутов
   *
   * @var array
   */
  protected $attrs = [];

  /**
   * Строка которую может вставить пользователь
   * например выставить <option> по умолчанию
   * или описательная строка
   *
   * @var string
   */
  protected $prepend = '';

  /**
   * Menu constructor
   */
  public function __construct($options = []) {
    $this->tpl = __DIR__ . '/menu_tpl/menu.php';
    $this->getOptions($options);
    $this->run();
  }

  /**
   * Получение настроек виджета внесенных пользователем
   * /public/menu/menu.php
   *
   * @param $options (настройки виджета)
   */
  protected function getOptions($options) {
    foreach ($options as $key => $value) {

      /**
       * Проверяем есть ли в данном классе свойство определенное пользователем
       * (tpl, cache, cacheKey, container ....)
       */
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  /**
   * Формируем меню
   */
  protected function run() {
    // Получаем объект кеша
    $cache = Cache::instance();
    // Получаем данные меню из кэша по ключу
    $this->menuHtml = $cache->get($this->cacheKey);

    // проверяем есть ли там данные
    if (!$this->menuHtml) {
      // если нету формируем
      $this->data = App::$app->getProperty('cats');

      if (!$this->data) {
        $this->data = \R::getAssoc("SELECT * FROM {$this->table}");
      }
      // получаем дерево меню
      $this->tree = $this->getTree();
      //debug($this->tree);
      $this->menuHtml = $this->getMenuHtml($this->tree);

      if ($this->cache) {
        $cache->set($this->cacheKey, $this->menuHtml, $this->cache);
      }

    }
    // если есть выводим
    $this->output();
  }

  /**
   * Вывод меню обернутого в контейнер +
   * дополнительные атрибуты
   */
  protected function output() {
    $attrs = '';

    if (!empty($this->attrs)) {
      foreach ($this->attrs as $key => $value) {
        $attrs .= " $key='$value' ";
      }
    }

    echo "<{$this->container} class='{$this->class}' $attrs>";
      echo $this->menuHtml;
    echo "</{$this->container}>";
  }

  /**
   * Формирование меню с учетом зависимости от родительского
   * элемента
   *
   * @return array
   */
  protected function getTree() {
    $tree = [];
    $data = $this->data;
    foreach ($data as $id => &$node) {
      if (!$node['parent_id']) {
        $tree[$id] = &$node;
      } else {
        $data[$node['parent_id']]['childs'][$id] = &$node;
      }
    }
    return $tree;
  }

  /**
   * Передаем меню по кускам с использованием разделителя
   *
   * @param        $tree // $category['childs']
   * @param string $tab
   *
   * @return string
   */
  protected function getMenuHtml($tree, $tab = '') {
    /**
     * $tab = '-'
     *
     * category 1
     * - category 1.1
     * -- category 1.1.1
     */
    $str = '';
    foreach ($tree as $id => $category) {
      $str .= $this->catToTemplate($category, $tab, $id);
    }
    return $str;
  }

  /**
   * Построение меню по заданому шаблону
   * по умолчанию шаблон: /app/widgets/menu/menu_tpl/menu.php
   * пользовательский: /public/menu/menu.php
   */
  protected function catToTemplate($category, $tab, $id) {
    ob_start();
    require $this->tpl;
    return ob_get_clean();
  }

}