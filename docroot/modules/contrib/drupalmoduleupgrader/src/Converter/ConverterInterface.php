<?php

namespace Drupal\drupalmoduleupgrader\Converter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;

/**
 * Base interface for all converters. Different converter plugin types might
 * extend this interface, but ultimately all converters must implement this.
 */
interface ConverterInterface extends ContainerFactoryPluginInterface {

  /**
   * Checks if this plugin can be executed, given the current module context.
   * This is called before analyze() or convert(), and if it returns a falsy
   * value, neither will be called.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   *
   * @return boolean
   */
  public function isExecutable(TargetInterface $target);

}
