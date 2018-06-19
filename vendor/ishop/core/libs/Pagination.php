<?php

namespace ishop\libs;

/**
 * Class Pagination
 *
 * @package ishop\libs
 */
class Pagination {

  /**
   * Номер текущей страницы
   *
   * @var string
   */
  public $currentPage;

  /**
   * Кол-во записей на страницу
   *
   * @var integer
   */
  public $perpage;

  /**
   * Общее к-во записей
   *
   * @var integer
   */
  public $total;

  /**
   * Общее к-во страниц
   *
   * @var integer
   */
  public $countPages;

  /**
   * @var string
   */
  public $uri;

  /**
   * Pagination constructor.
   *
   * @param $page
   * @param $perpage
   * @param $total
   */
  public function __construct($page, $perpage, $total) {
    $this->perpage = $perpage;
    $this->total = $total;
    $this->countPages = $this->getCountPages();
    $this->currentPage = $this->getCurrentPage($page);
    $this->uri = $this->getParams();
  }

  /**
   * Формируем html код для пагинации
   *
   * @return string
   */
  public function getHtml(){
    $back = null; // ссылка НАЗАД
    $forward = null; // ссылка ВПЕРЕД
    $startpage = null; // ссылка В НАЧАЛО
    $endpage = null; // ссылка В КОНЕЦ
    $page2left = null; // вторая страница слева
    $page1left = null; // первая страница слева
    $page2right = null; // вторая страница справа
    $page1right = null; // первая страница справа

    if( $this->currentPage > 1 ){
      $back = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage - 1). "'>&lt;</a></li>";
    }
    if( $this->currentPage < $this->countPages ){
      $forward = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 1). "'>&gt;</a></li>";
    }
    if( $this->currentPage > 3 ){
      $startpage = "<li><a class='nav-link' href='{$this->uri}page=1'>&laquo;</a></li>";
    }
    if( $this->currentPage < ($this->countPages - 2) ){
      $endpage = "<li><a class='nav-link' href='{$this->uri}page={$this->countPages}'>&raquo;</a></li>";
    }
    if( $this->currentPage - 2 > 0 ){
      $page2left = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage-2). "'>" .($this->currentPage - 2). "</a></li>";
    }
    if( $this->currentPage - 1 > 0 ){
      $page1left = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage-1). "'>" .($this->currentPage-1). "</a></li>";
    }
    if( $this->currentPage + 1 <= $this->countPages ){
      $page1right = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 1). "'>" .($this->currentPage+1). "</a></li>";
    }
    if( $this->currentPage + 2 <= $this->countPages ){
      $page2right = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 2). "'>" .($this->currentPage + 2). "</a></li>";
    }

    return '<ul class="pagination">' . $startpage.$back.$page2left.$page1left.'<li class="active"><a>'.$this->currentPage.'</a></li>'.$page1right.$page2right.$forward.$endpage . '</ul>';
  }

  /**
   * Переводим object Pagination в string
   *
   * @return string
   */
  public function __toString() {
    return $this->getHtml();
  }

  /**
   * Подсчитываем к-во страниц для вывода
   */
  public function getCountPages() {
    return ceil($this->total / $this->perpage) ?: 1;
  }

  /**
   * Получает и обрабатывает значение текущей страницы
   *
   * @param $page
   *
   * @return int
   */
  public function getCurrentPage($page) {
    if (!$page || $page < 1) $page = 1;
    if ($page > $this->countPages) $page = $this->countPages;
    return $page;
  }

  /**
   * Высчитываем с какой записи начинаем выборку из БД
   *
   * @return int
   */
  public function getStart() {
    return ($this->currentPage - 1) * $this->perpage;
  }

  /**
   * Получает и обрабатывает $uri
   * (/category/men?page=2&sort=name)
   */
  public function getParams() {
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $uri = $url[0] . '?';

    if (isset($url[1]) && $url[1] != '') {
      $params = explode('&', $url[1]);

      foreach ($params as $param) {
        if (!preg_match("#page=#", $param)) $uri .= "{$param}" ."&";
      }
    }

    return urldecode($uri);
  }
}