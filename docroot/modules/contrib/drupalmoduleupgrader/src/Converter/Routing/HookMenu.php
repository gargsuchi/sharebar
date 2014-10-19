<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\Router as Drupal7Router;
use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper as Drupal7Route;
use Drupal\drupalmoduleupgrader\Converter\Routing\RouterBase as Drupal8Router;

/**
 * Parses hook_menu() and stores the routes that are discovered.
 */
class HookMenu {

  /**
   * The source routes (from Drupal 7).
   *
   * @var RouterInterface
   */
  protected $sourceRoutes;

  /**
   * The destination routes (as in a routing.yml file).
   *
   * @var RouterInterface
   */
  protected $destinationRoutes;

  /**
   * Maps Drupal 7 paths to Drupal 8 route names.
   *
   * @var string[]
   */
  protected $routeMap = [];

  /**
   * The route converters' plugin manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $routeConverters;

  /**
   * Constructs a HookMenu object.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $route_converters
   *   The route converters.
   */
  public function __construct(PluginManagerInterface $route_converters) {
    $this->routeConverters = $route_converters;
  }

  /**
   * Returns the collection of routes in the source.
   *
   * @param TargetInterface $target
   *   The module that is being converted.
   *
   * @return RouterInterface
   *   The requested link collection.
   */
  public function getSourceRoutes(TargetInterface $target) {
    if (empty($this->sourceRoutes)) {
      $this->convert($target);
    }
    return $this->sourceRoutes;
  }

  /**
   * Returns the collection of routes in the destination.
   *
   * @param TargetInterface $target
   *   The module that is being converted.
   *
   * @return RouterInterface
   *   The requested route collection.
   */
  public function getDestinationRoutes(TargetInterface $target) {
    if (empty($this->destinationRoutes)) {
      $this->convert($target);
    }
    return $this->destinationRoutes;
  }

  /**
   * Returns the destination route for the given source path.
   *
   * @param string $path
   *   The source path, as defined in hook_menu().
   *
   * @return \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\RouteWrapper|NULL
   *   The destination route.
   */
  public function getDestinationRoute($path) {
    return $this->destinationRoutes->get($this->routeMap[$path]);
  }

  /**
   * Converts the source routes to the destination routes.
   *
   * @param TargetInterface $target
   *   The module that is being converted.
   */
  public function convert(TargetInterface $target) {
    // @todo These are currently hardcoded on the D7 -> D8 conversion. Make this
    //   configurable.
    $this->sourceRoutes = new Drupal7Router();
    $this->destinationRoutes = new Drupal8Router();
    $this->routeMap = [];

    // Get the hook_menu() implementation and execute the code. This will make
    // the function available so we can call it to get the menu items.
    /** @var \Pharborist\Functions\FunctionDeclarationNode $hook */
    $hook = $target->getIndexer()->get('hook', 'menu')->get(0);
    // Don't create the function twice, or it we'll get a fatal error.
    if (! function_exists($hook->getName()->getText())) {
      eval($hook->getText());
    }
    $items = call_user_func($hook->getName()->getText());

    foreach ($items as $path => $item) {
      $this->sourceRoutes->addRoute(new Drupal7Route($path, $item));
    }
    $this->sourceRoutes->finalize();

    foreach ($this->sourceRoutes as $path => $route) {
      /** @var Drupal7\RouteWrapper $route */
      // If the route hasn't got a page callback...don't even try.
      if (!$route->containsKey('page callback')) {
        continue;
      }

      // Get the appropriate route converter, which will build the route
      // definition.
      $plugin_id = $route['page callback'];
      if (!$this->routeConverters->hasDefinition($plugin_id)) {
        $plugin_id = 'default';
      }

      $converter = $this->routeConverters->createInstance($plugin_id, [ 'route' => $route ]);
      $converter->init($target);
      // @todo buildRoute() is destructive and needs to be changed to
      // buildRouteDefinition() once it's refactored enough to not result
      // in a fatal error.
      $d8_route = $converter->buildRoute($target, $route);
      $this->destinationRoutes->addRoute($d8_route);
      $this->routeMap[$path] = $d8_route->getIdentifier();
    }
    $this->destinationRoutes->finalize();

    foreach ($this->sourceRoutes->getDefaultLocalTasks() as $path => $route) {
      /** @var Drupal7\RouteWrapper $route */
      if ($route->hasParent()) {
        $parent = (string) $route->getParent()->getPath();
        $this->routeMap[$path] = $this->routeMap[$parent];
      }
    }
  }

}
