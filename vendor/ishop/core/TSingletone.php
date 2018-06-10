<?php

namespace ishop;

/**
 * Trait TSingletone
 *
 * @package ishop
 */
trait TSingletone {

  private static $instance;

  /**
   * @return \ishop\TSingletone
   */
  public static function instance() {
    if (self::$instance === NULL) {
      self::$instance = new self;
    }
    return self::$instance;
  }

}