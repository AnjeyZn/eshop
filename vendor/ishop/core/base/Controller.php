<?php


namespace ishop\base;

/**
 * Class Controller
 *
 * @package ishop\base
 */
abstract class Controller {

  /**
   * Текущий путь
   *
   * @var
   */
  public $route;

  /**
   * Текущий контроллер
   *
   * @var
   */
  public $controller;

  /**
   * Текущая модель
   *
   * @var
   */
  public $model;

  /**
   * Текущий вид
   *
   * @var
   */
  public $view;

  /**
   * Префикс ('', 'admin')
   *
   * @var
   */
  public $prefix;

  /**
   * Текущий шаблон страницы
   *
   * @var
   */
  public $layout;

  /**
   * Данные контента
   *
   * @var array
   */
  public $data = [];

  /**
   * Метаданные
   *
   * @var array
   */
  public $meta = ['title' => '', 'desc' => '', 'keywords' => ''];

  /**
   * Controller constructor.
   *
   * @param $route
   */
  public function __construct($route) {
    $this->route = $route;
    $this->controller = $route['controller'];
    $this->model = $route['controller'];
    $this->view = $route['action'];
    $this->prefix = $route['prefix'];
  }

  /**
   * Создание вида
   */
  public function getView() {
    $viewObject = new View($this->route, $this->layout, $this->view, $this->meta);
    $viewObject->render($this->data);
  }

  /**
   * Установка данных (контента)
   *
   * @param $data
   */
  public function set($data) {
    $this->data = $data;
  }

  /**
   * Установка мета данных
   *
   * @param string $title
   * @param string $desc
   * @param string $keywords
   */
  public function setMeta($title = '', $desc = '', $keywords = '') {
    $this->meta['title'] = $title;
    $this->meta['desc'] = $desc;
    $this->meta['keywords'] = $keywords;
  }

}