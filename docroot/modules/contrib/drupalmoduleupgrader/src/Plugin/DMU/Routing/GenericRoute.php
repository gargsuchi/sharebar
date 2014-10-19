<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\Routing\GenericRoute.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\Routing;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper;
use Drupal\drupalmoduleupgrader\Converter\Routing\RouteConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Functions\FunctionDeclarationNode;

/**
 * @Converter(
 *  id = "default",
 *  description = @Translation("Converts a menu item to a _content route."),
 *  message = @Translation("Page callback functions must be moved into controller classes."),
 *  change_notice = 2119699
 * )
 */
class GenericRoute extends RouteConverterBase {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getName(TargetInterface $target, RouteWrapper $route) {
    $name = $target->id() . '.' . $this->unPrefix($route['page callback'], $target->id());

    $arguments = array_filter($route['page arguments'], 'is_string');
    if ($arguments) {
      $name .= '_' . implode('_', $arguments);
    }

    return $name;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target, RouteWrapper $route) {
    parent::convert($target, $route);
    $this->route['page callback']->cloneAsMethodOf($this->controller);
    $this->writeClass($target, $this->getController($target));
  }

  protected function getController(TargetInterface $target) {
    $indexer = $target->getIndexer();

    if ($indexer->has('class', 'DefaultController')) {
      $this->controller = $indexer->get('class', 'DefaultController')->get(0);
    }
    else {
      // Create the default controller.
      $render = [
        '#theme' => 'dmu_controller',
        '#module' => $target->id(),
      ];
      $this->controller = $this->parse($render);
      $this->writeClass($target, $this->controller);
      $indexer->add('class', 'DefaultController', $target->getPath('src/Controller/DefaultController.php'));
    }
    return $this->controller;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRouteDefinition(TargetInterface $target, RouteWrapper $route) {
    $definition = parent::buildRouteDefinition($target, $route);
    $definition->setDefault('_content', $this->getController($target)->getName()->getAbsolutePath() . '::' . $route['page callback']);

    return $definition;
  }

}
