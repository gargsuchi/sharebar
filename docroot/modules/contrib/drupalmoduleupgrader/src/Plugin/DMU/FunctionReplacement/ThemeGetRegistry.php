<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\ThemeGetRegistry.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\FalseNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\StringNode;

/**
 * @Converter(
 *  id = "theme_get_registry",
 *  description = @Translation("Rewrites calls to theme_get_registry()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2137545",
 *      "title" = @Translation("<code>theme_get_registry()</code> is now two different methods of the <var>theme.registry</var> service")
 *    }
 *  },
 *  message = @Translation("The theme registry is now a service.")
 * )
 */
class ThemeGetRegistry extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    return ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(StringNode::fromValue('theme.registry'))
      ->appendMethodCall(($arguments && $arguments[0] instanceof FalseNode) ? 'getRuntime' : 'get');
  }

}
