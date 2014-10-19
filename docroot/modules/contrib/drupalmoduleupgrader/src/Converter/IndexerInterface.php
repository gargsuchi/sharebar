<?php

namespace Drupal\drupalmoduleupgrader\Converter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;

/**
 * Interface implemented by plugins which can index part of a target module.
 */
interface IndexerInterface extends ContainerFactoryPluginInterface {

  /**
   * Indexes parts of the target module.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   */
  public function index(TargetInterface $target);

}
