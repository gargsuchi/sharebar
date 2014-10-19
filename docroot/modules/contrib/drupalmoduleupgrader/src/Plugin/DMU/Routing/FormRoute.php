<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\Routing\FormRoute.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\Routing;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\ParameterMap;
use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper;
use Drupal\drupalmoduleupgrader\Converter\Routing\RouteConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Drupal\drupalmoduleupgrader\Utility\FormConverter;
use Pharborist\Filter;

/**
 * @Converter(
 *  id = "drupal_get_form",
 *  description = @Translation("Converts a drupal_get_form() menu item to a _form route."),
 *  message = @Translation("drupal_get_form() is no longer a valid page callback and must be replaced with a route to a class implementing \Drupal\Core\Form\FormInterface."),
 * )
 */
class FormRoute extends RouteConverterBase {

  /**
   * {@inheritdoc}
   */
  public function getName(TargetInterface $target, RouteWrapper $route) {
    $name = $target->id() . '.' . $this->unPrefix($route['page arguments'][0], $target->id());

    $arguments = array_filter(array_slice($route['page arguments'], 1), 'is_string');
    if ($arguments) {
      $name .= '_' . implode('_', $arguments);
    }

    return $name;
  }

  public function init(TargetInterface $target) {
    // Get the form ID. If drupal_get_form() was the page callback, the form ID
    // *will* be the first argument, or this is a malformed route that deserves
    // every error it causes.
    $form_builder = $this->route['page arguments'][0];

    // If the module doesn't define the form builder, getFunction() will throw an exception.
    // But then again, if the module doesn't define the form builder...we can't really
    // do anything to convert the route, now can we?
    $this->route['page callback'] = clone $target->getIndexer()->get('function', $form_builder)->get(0);

    // Ignore the form builder's first two parameters, which will always be $form and $form_state;
    // they are never part of the route.
    $parameters = array_slice($this->route['page callback']->getParameters(), 2);

    // Shift the form ID off the arguments array.
    $arguments = array_slice($this->route['page arguments'], 1);

    // Both arrays need to start at the same index in order for ParameterMap to work
    // properly. ParameterMap expects the calling code to do this step.
    $parameters = $this->bumpKeys($parameters, 2);
    $arguments = $this->bumpKeys($arguments, 2);

    $this->route['page arguments'] = new ParameterMap($this->route->getPath(), $parameters, $arguments);
  }

  /**
   * Returns a copy of the input array with the keys increased by $offset. This
   * only works on numerically indexed arrays; I don't know what it does to
   * associative arrays, but probably nothing good.
   *
   * @param array $input
   *  The input array.
   *
   * @param int $offset
   *  The offset to add to the keys.
   *
   * @return array
   */
  private function bumpKeys(array $input, $offset = 0) {
    $output = [];

    foreach ($input as $key => $value) {
      $output[ $key + $offset ] = $value;
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target, RouteWrapper $route) {
    $builder = $this->route['page callback'];
    $this->controller = (new FormConverter($target, $builder))->generate();
    $this->writeClass($target, $this->controller);

    parent::convert($target, $route);
  }

  /**
   * {@inheritdoc}
   */
  public function buildRouteDefinition(TargetInterface $target, RouteWrapper $route) {
    $definition = parent::buildRouteDefinition($target, $route);
    $definition->setDefault('_form', '\Drupal\\' . $target->id() . '\\Form\\' . $this->toTitleCase($route['page arguments'][0]));

    return $definition;
  }

}
