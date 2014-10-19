<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\ParameterMapBase.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing;

use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;

/**
 * Represents a set of parameter bindings for a particular path, callback,
 * and set of arguments.
 */
abstract class ParameterMapBase implements ParameterMapInterface {

  /**
   * @var \Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface
   */
  protected $path;

  /**
   * @var ParameterBindingInterface[]
   */
  protected $bindings = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(PathUtilityInterface $path, array $parameters) {
    $this->path = $path;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    return new \ArrayIterator($this->bindings);
  }

  /**
   * {@inheritdoc}
   */
  public function merge(ParameterMapInterface $map) {
    foreach ($map as $binding) {
      $this->addBinding($binding);
    }
  }

}
