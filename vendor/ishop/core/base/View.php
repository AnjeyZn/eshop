<?php

namespace ishop\base;

/**
 * Class View
 *
 * @package ishop\base
 */
class View {

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
  public $meta = [];

  /**
   * View constructor.
   *
   * @param        $route
   * @param string $layout
   * @param string $view
   * @param        $meta
   */
  public function __construct($route, $layout = '', $view = '', $meta) {
    $this->route = $route;
    $this->controller = $route['controller'];
    $this->model = $route['controller'];
    $this->view = $view;
    $this->prefix = $route['prefix'];
    $this->meta = $meta;

    if ($layout === FALSE) {
      $this->layout = FALSE;
    } else {
      $this->layout = $layout ?: LAYOUT;
    }
  }

  /**
   * Формируем страницу
   *
   * @param $data
   *
   * @throws \Exception
   */
  public function render($data) {

    if (is_array($data)) extract($data);

    $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";

    if (is_file($viewFile)) {
      ob_start();
      require_once $viewFile;
      $content = ob_get_clean();
    } else {
      throw new \Exception("Не найден вид: {$viewFile}", 500);
    }

    if (FALSE !== $this->layout) {
      $layoutFile = APP . "/views/layouts/{$this->layout}.php";

      if (is_file($layoutFile)) {
        require_once $layoutFile;
      } else {
        throw new \Exception("Не найден шаблон: {$this->layout}", 500);
      }
    }
  }

  /**
   * Вывод метаданных
   *
   * @return string
   */
  public function getMeta() {
    $output = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL;
    $output .= '<meta name="description" content="' . $this->meta['desc'] . '">' . PHP_EOL;
    $output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;

    return $output;
  }

}