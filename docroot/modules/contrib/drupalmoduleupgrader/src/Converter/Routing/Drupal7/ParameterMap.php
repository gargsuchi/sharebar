<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\ParameterMap.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7;

use Drupal\drupalmoduleupgrader\Converter\Routing\ParameterBindingInterface;
use Drupal\drupalmoduleupgrader\Converter\Routing\ParameterMapBase;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal7\PathComponent;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal8\PathComponent as PathComponent8x;
use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;
use Symfony\Component\Routing\Route as Drupal8Route;

/**
 * Represents a set of parameter bindings for a particular path, callback,
 * and set of arguments in a Drupal 7 route.
 */
class ParameterMap extends ParameterMapBase {

  /**
   * @var integer
   */
  private $_length = 0;

  /**
   * {@inheritdoc}
   */
  public function __construct(PathUtilityInterface $path, array $parameters, array $arguments = []) {
    parent::__construct($path, $parameters);

    $this->_length = sizeof($path);

    while ($parameters) {
      $argument = $arguments ? array_shift($arguments) : ParameterBinding::NO_ARGUMENT;
      $this->addBinding(new ParameterBinding($path, array_shift($parameters), $argument));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addBinding(ParameterBindingInterface $binding) {
    $value = $binding->getValue();

    // The binding will return a PathComponent if it expects to be physically
    // represented in the path, whether or not it already is.
    if ($value instanceof PathComponent) {
      if ($binding->inPath()) {
        $key = $binding->getArgument();
      }
      else {
        $key = $this->path->indexOf($value);
        if ($key === FALSE) {
          $key = $this->_length++;
        }
      }

      if (isset($this->bindings[$key])) {
        $binding->enslave($this->bindings[$key]);
      }
    }
    else {
      $key = $binding->getParameter()->getName();
    }

    $this->bindings[$key] = $binding;
  }

  /**
   * Applies the parameter map to the path, modifying it as needed.
   */
  public function applyPath() {
    foreach ($this->bindings as $key => $binding) {
      if (is_integer($key)) {
        $this->path[$key] = new PathComponent8x('{' . $binding->getParameter()->getName() . '}');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function applyRoute(Drupal8Route $route) {
    $this->applyPath();

    foreach ($this->bindings as $key => $binding) {
      $parameter = $binding->getParameter();

      /** @var ParameterBinding $binding */
      if (is_integer($key)) {
        if ($parameter->isOptional()) {
          // @todo Don't use eval().
          $value = eval('return ' . $parameter->getValue() . ';');
          $route->setDefault($parameter->getName(), $value);
        }
      }
      elseif ($binding->hasArgument()) {
        $route->setDefault($parameter->getName(), $binding->getValue());
      }
    }
    $route->setPath($this->path->__toString());
  }

  public function finalize() {
    foreach ($this->bindings as $key => $binding) {
      /** @var ParameterBinding $binding */
      if (is_integer($key)) {
        $binding->conform();
      }
    }
  }

}
