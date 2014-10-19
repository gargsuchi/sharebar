<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\ModuleInvokeAll.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ArrayNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "module_invoke_all",
 *  description = @Translation("Rewrites calls to module_invoke_all()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1894902",
 *      "title" = @Translation("<code>module_invoke_all()</code> replaced by <var>module_handler</var> service")
 *    }
 *  },
 *  message = @Translation("<code>module_invoke_all()</code> is now a method of the <var>module_handler</var> service.")
 * )
 */
class ModuleInvokeAll extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    return ClassMethodCallNode::create('\Drupal', 'moduleHandler')
      ->appendMethodCall('invokeAll')
      ->appendArgument(array_shift($arguments))
      ->appendArgument(ArrayNode::create($arguments));
  }

}
