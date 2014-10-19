<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\URL.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ArrayNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;
use Pharborist\StringNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Converter(
 *  id = "url",
 *  description = @Translation("Rewrites calls to url()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2346779",
 *      "title" = @Translation("<code>url()</code> replaced by URL generation API")
 *    }
 *  },
 *  message = @Translation("<code>url()</code> has been removed."),
 *  require_rewrite = true
 * )
 */
class URL extends FunctionCallRewriter implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Routing\RouteProviderInterface
   */
  protected $routeProvider;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteProviderInterface $route_provider) {
    $this->routeProvider = $route_provider;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('router.route_provider'));
  }

  protected function routeExists($path) {
    $path = '/' . $path;

    // If there's a scheme in the URL, consider this an external URL and don't even
    // try to rewrite it.
    $scheme = parse_url($path, PHP_URL_SCHEME);
    if (isset($scheme)) {
      return FALSE;
    }
    else {
      $routes = $this->routeProvider->getRoutesByPattern($path);
      return (sizeof($routes) > 0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function canModify(FunctionCallNode $call) {
    $arguments = $call->getArguments();
    return ($arguments[0] instanceof StringNode && $this->routeExists($arguments[0]->toValue()));
  }

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    // The first parameter to url() was either an internal path, or a full
    // external URL.
    $url = $arguments[0]->toValue();

    // If the URL has a scheme (e.g., http://), it's external. Otherwise, it's
    // internal and we'll want to find the corresponding route.
    if (parse_url($url, PHP_URL_SCHEME)) {
      return ClassMethodCallNode::create('\Drupal\Core\Url', 'fromUri')
        ->appendArgument(clone $arguments[0]);
    }
    else {
      $route = $this->routeProvider->getRoutesByPattern('/' . $url)->getIterator()->key();
      return ClassMethodCallNode::create('\Drupal\Core\Url', 'fromRoute')
        ->appendArgument(StringNode::fromValue($route));
    }
  }

}
