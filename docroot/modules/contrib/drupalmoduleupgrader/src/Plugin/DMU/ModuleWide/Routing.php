<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Routing.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper as Drupal7Route;
use Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Converter(
 *  id = "Routing",
 *  description = @Translation("Converts parts of hook_menu() to the Drupal 8 routing system."),
 *  hook = "menu"
 * )
 */
class Routing extends HookConverterBase {

  /**
   * The hook_menu() converter.
   *
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu
   */
  protected $hookMenu;

  /**
   * The route converters' plugin manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $routeConverters;

  /**
   * Constructs a Routing object.
   *
   * @param array $configuration
   *   Additional configuration for the plugin.
   * @param string $plugin_id
   *   The plugin ID, will be "Links".
   * @param mixed $plugin_definition
   *   The plugin definition as derived from the annotations.
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu $hook_menu_converter
   *   The hook_menu() converter.
   *
   * @param PluginManagerInterface $route_converters
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PluginManagerInterface $route_converters, HookMenu $hook_menu) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeConverters = $route_converters;
    $this->hookMenu = $hook_menu;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $route_converters = $container->get('plugin.manager.drupalmoduleupgrader.route');
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $route_converters,
      new HookMenu($route_converters)
    );
  }


  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    parent::analyze($target);

    foreach ($this->hookMenu->getSourceRoutes($target) as $route) {
      if ($route->containsKey('page callback')) {
        $this->getRouteConverter($route)->analyze($target);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    foreach ($this->hookMenu->getSourceRoutes($target) as $path => $route) {
      /** @var \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper $route */
      if ($route->containsKey('page callback')) {
        /** @var \Drupal\drupalmoduleupgrader\Converter\Routing\RouteConverterInterface $converter */
        $converter = $this->getRouteConverter($route);
        $converter->init($target);
        $converter->convert($target, $route);
      }
    }

    $routing = [];
    foreach ($this->hookMenu->getDestinationRoutes($target) as $name => $route) {
      $routing[$name] = [
        'path' => $route->getPath()->__toString(),
        'defaults' => $route->getDefaults(),
        'requirements' => $route->getRequirements(),
      ];
    }
    $this->writeInfo($target, 'routing', $routing);
  }

  /**
   * Gets the appropriate route converter for a Drupal 7 router item, if one exists.
   *
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper $route
   *  The original Drupal 7 route.
   *
   * @return \Drupal\drupalmoduleupgrader\Converter\ConverterInterface
   */
  private function getRouteConverter(Drupal7Route $route) {
    $plugin_id = $route['page callback'];

    // Check if there is a route converter to handle this page callback.
    if (! $this->routeConverters->hasDefinition($plugin_id)) {
      $plugin_id = 'default';
    }

    return $this->routeConverters->createInstance($plugin_id, [ 'route' => $route ]);
  }

}
