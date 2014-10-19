<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Watchdog.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ArrayNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "watchdog",
 *  description = @Translation("Converts calls to watchdog() to \Drupal::logger()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2270941",
 *      "title" = @Translation("<code>watchdog()</code> is deprecated")
 *    }
 *  },
 *  message = @Translation("<code>watchdog()</code> is scheduled for removal.")
 * )
 */
class Watchdog extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    // We'll call a specific method on the logger object, depending on the
    // severity passed in the original function call (if any). If there are
    // at least four arguments, a severity was passed.
    $method = sizeof($arguments) > 3 ? strToLower(subStr($arguments[3], 9)) : 'notice';

    // If there were at least three arguments, a context array was passed.
    $context = sizeof($arguments) > 2 ? clone $arguments[2] : ArrayNode::create([]);

    return ClassMethodCallNode::create('\Drupal', 'logger')
      ->appendArgument(clone $arguments[0])
      ->appendMethodCall($method)
      ->appendArgument(clone $arguments[1])
      ->appendArgument($context);
  }

}
