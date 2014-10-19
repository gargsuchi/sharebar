<?php

namespace Drupal\drupalmoduleupgrader\Converter;

use Drupal\drupalmoduleupgrader\TargetInterface;

/**
 * Interface for plugins which can delete dead legacy code from a target module.
 */
interface CleanerInterface {

  /**
   * Deletes old, dead code and legacy cruft from the target module.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   */
  public function clean(TargetInterface $target);

}
