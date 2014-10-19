<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\ParameterMapInterface.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing;

use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;
use Symfony\Component\Routing\Route;

/**
 * Defines a set of parameter bindings between a route path and a single callback.
 */
interface ParameterMapInterface extends \IteratorAggregate {

  /**
   * Constructs a parameter map.
   *
   * @param \Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface $path
   *  The path to map parameters to, which may be modified in-place by apply().
   * @param \Pharborist\Functions\ParameterNode[] $parameters
   *  The callback parameters.
   */
  public function __construct(PathUtilityInterface $path, array $parameters);

  /**
   * Adds a binding to this map, overwriting the existing one if there is a
   * conflict (it's up to the implementing class to determine what constitutes
   * a conflict.)
   *
   * @param ParameterBindingInterface $binding
   *  The binding to add.
   */
  public function addBinding(ParameterBindingInterface $binding);

  /**
   * Apply the parameter map to a Drupal 8 route, modifying it as needed.
   *
   * @param \Symfony\Component\Routing\Route $route
   *  The route to process.
   */
  public function applyRoute(Route $route);

  /**
   * Merge another parameter map into this one. Bindings from the incoming map
   * should 'win', although the specifics are up to the implementing classes.
   *
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\ParameterMapInterface $map
   *  The parameter map to merge.
   */
  public function merge(ParameterMapInterface $map);

}
