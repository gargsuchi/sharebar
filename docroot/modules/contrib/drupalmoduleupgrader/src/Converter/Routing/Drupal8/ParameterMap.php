<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\ParameterMap.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8;

use Drupal\drupalmoduleupgrader\Converter\Routing\ParameterBindingInterface;
use Drupal\drupalmoduleupgrader\Converter\Routing\ParameterMapBase;
use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;
use Symfony\Component\Routing\Route;

/**
 * Represents a set of path-based parameter bindings in a Drupal 8 route.
 */
class ParameterMap extends ParameterMapBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(PathUtilityInterface $path, array $parameters) {
    parent::__construct($path, $parameters);

    while ($parameters) {
      $this->addBinding(new ParameterBinding($path, array_shift($parameters)));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addBinding(ParameterBindingInterface $binding) {
    $key = $binding->getParameter()->getName();

    if (isset($this->bindings[$key])) {
      $binding->enslave($this->bindings[$key]);
    }

    $this->bindings[$key] = $binding;
  }

  /**
   * {@inheritdoc}
   */
  public function applyRoute(Route $route) {
    foreach ($this->bindings as $key => $binding) {
      /** @var \Pharborist\Functions\ParameterNode $parameter */
      $parameter = $binding->getParameter();
      if ($parameter->isRequired() && (! $binding->inPath())) {
        $route->setDefault($key, eval('return ' . $parameter->getValue() . ';'));
      }
    }
  }

  public function finalize() {
    foreach ($this->bindings as $key => $binding) {
      $binding->conform();
    }
  }

}
