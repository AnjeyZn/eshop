<?php

namespace app\widgets\filter;

use ishop\Cache;

/**
 * Class Filter
 *
 * @package app\widgets\filter
 */
class Filter {

  /**
   * Группы атрибутов
   *
   * @var
   */
  public $groups;

  /**
   * Аттрибуты
   *
   * @var
   */
  public $attrs;

  /**
   * Путь к шаблону
   *
   * @var string
   */
  public $tpl;

  /**
   * Filter constructor
   */
  public function __construct() {
    $this->tpl = __DIR__ . '/filter_tpl.php';
    $this->run();
  }

  /**
   * Запуск фильтра
   */
  public function run() {
    $cache = Cache::instance();

    $this->groups = $cache->get('filter_group');

    if (!$this->groups) {
      $this->groups = $this->getGroups();
      $cache->set('filter_group', $this->groups, 30);
    }

    $this->attrs = $cache->get('filter_attrs');

    if (!$this->attrs) {
      $this->attrs = $this->getAttrs();
      $cache->set('filter_attrs', $this->attrs, 30);
    }

    $filters = $this->getHtml();
    echo $filters;
  }

  /**
   * @return string
   */
  protected function getHtml() {
    ob_start();
    require $this->tpl;
    return ob_get_clean();
  }

  /**
   * Получаем группы аттрибутов
   *
   * @return array
   */
  protected function getGroups() {
    return \R::getAssoc('SELECT id, title FROM attribute_group');
  }

  /**
   * Получаем аттрибуты
   *
   * @return array
   */
  protected function getAttrs() {
    $data = \R::getAssoc('SELECT * FROM attribute_value');
    $attrs = [];

    foreach ($data as $key => $value) {
      $attrs[$value['attr_group_id']][$key] = $value['value'];
    }
    return $attrs;
  }

}