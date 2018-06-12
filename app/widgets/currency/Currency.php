<?php

namespace app\widgets\currency;


use ishop\App;
use R;

/**
 * Class Currency
 *
 * @package app\widgets\currency
 */
class Currency {

  /**
   * Выбор шаблона, по умолчанию select
   *
   * @var string
   */
  protected $tpl;

  /**
   * Список валют
   *
   * @var array
   */
  protected $currencies;

  /**
   * Активная валюта
   *
   * @var array
   */
  protected $currency;

  public function __construct() {
    $this->tpl = __DIR__ . '/currency_tpl/currency.php';
    $this->run();
  }

  protected function run() {
    $this->currencies = App::$app->getProperty('currencies');
    $this->currency = App::$app->getProperty('currency');
    echo $this->getHtml();
  }

  /**
   * Получаем список всех валют
   * (первой идет базовая валюта "$")
   *
   * @return array
   */
  public static function getCurrencies() {
    return R::getAssoc("SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC");
  }

  /**
   * Получаем активную валюту
   *
   * @param $currencies
   *
   * @return array
   */
  public static function getCurrency($currencies) {
    if (isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)) {
      $key = $_COOKIE['currency'];
    } else {
      // получаем текущий элемент массива (по умолч. базовая валюта "key = USD")
      $key = key($currencies);
    }
    // получаем все параметры активной валюты по ее ключу (key = USD, or UAH, EUR)
    $currency = $currencies[$key];
    $currency['code'] = $key;

    return $currency;
  }

  /**
   * Подключаем шаблон
   */
  public function getHtml() {
    ob_start();
    require_once $this->tpl;
    return ob_get_clean();
  }

}