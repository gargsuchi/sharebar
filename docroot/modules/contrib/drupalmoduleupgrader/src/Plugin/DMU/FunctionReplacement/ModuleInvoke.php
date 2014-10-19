<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\ModuleInvoke.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ArrayNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "module_invoke",
 *  description = @Translation("Rewrites calls to module_invoke()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1894902",
 *      "title" = @Translation("<code>module_invoke()</code> replaced by <var>module_handler</var> service")
 *    }
 *  },
 *  message = @Translation("<code>module_invoke()</code> is now a method of the <var>module_handler</var> service.")
 * )
 */
class ModuleInvoke extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    $invoke = ClassMethodCallNode::create('\Drupal', 'moduleHandler')
      ->appendMethodCall('invoke')
      ->appendArgument(array_shift($arguments)->remove())
      ->appendArgument(array_shift($arguments)->remove());

    if ($arguments) {
      $invoke->appendArgument(ArrayNode::create($arguments));
    }

    return $invoke;
  }

}
