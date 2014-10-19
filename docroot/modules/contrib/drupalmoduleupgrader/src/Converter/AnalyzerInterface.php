<?php

namespace Drupal\drupalmoduleupgrader\Converter;

use Drupal\drupalmoduleupgrader\TargetInterface;

/**
 * Interface for plugins which can analyze a target module and flag potential
 * or existing issues.
 */
interface AnalyzerInterface {

  /**
   * Deletes old, dead code and legacy cruft from the target module.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   */
  public function analyze(TargetInterface $target);

}
