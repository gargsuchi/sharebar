<?php

namespace Drupal\drupalmoduleupgrader\Converter\Routing;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper;
use Drupal\drupalmoduleupgrader\TargetInterface;

/**
 * Defines a route converter, which converts a Drupal 7 router item to a
 * Drupal 8 Symfony route. These plugins are NOT responsible for converting
 * *links* (including tabs or local actions), only the actual route.
 */
interface RouteConverterInterface {

  /**
   * Generates the route's machine-readable name.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   *  The target module.
   * @param Drupal7\RouteWrapper $route
   *  The original Drupal 7 route.
   *
   * @return string
   */
  public function getName(TargetInterface $target, RouteWrapper $route);

  /**
   * Builds the Drupal 8 path for the route.
   *
   * @return \Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface
   */
  public function buildPath();

  /**
   * Builds the Drupal 8 route definition for the route, without making any
   * changes to the source module.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   *  The target module.
   * @param Drupal7\RouteWrapper $route
   *  The original Drupal 7 route to build the definition for.
   *
   * @return \Symfony\Component\Routing\Route
   */
  public function buildRouteDefinition(TargetInterface $target, RouteWrapper $route);

  /**
   * Builds a Drupal 8 route definition, modifying whatever part of the target
   * module needs to be modified.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   *  The target module.
   * @param Drupal7\RouteWrapper $route
   *  The original Drupal 7 route to convert.
   *
   * @return \Drupal\drupalmoduleupgrader\Converter\Routing\RouteWrapperInterface
   */
  public function buildRoute(TargetInterface $target, RouteWrapper $route);

}
