<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\UserAccess.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\ObjectMethodCallNode;
use Pharborist\VariableNode;

/**
 * @Converter(
 *  id = "user_access",
 *  description = @Translation("Rewrites calls to user_access()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2049309",
 *      "title" = @Translation("<code>user_access()</code> got converted to a method on the user/account interface")
 *    }
 *  },
 *  message = @Translation("<code>user_access()</code> is now <code>AccountInterface::hasPermission()</code>.")
 * )
 */
class UserAccess extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    if (isset($arguments[1]) && $arguments[1] instanceof VariableNode) {
      $rewritten = ObjectMethodCallNode::create(clone $arguments[1], 'hasPermission');
    }
    else {
      $rewritten = ClassMethodCallNode::create('\Drupal', 'currentUser')->appendMethodCall('hasPermission');
    }

    return $rewritten->appendArgument(clone $arguments[0]);
  }

}
