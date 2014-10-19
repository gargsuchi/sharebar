<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\CTools\CToolsObjectCacheSet.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\CTools;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;

/**
 * @Converter(
 *  id = "ctools_object_cache_set",
 *  description = @Translation("Converts calls to ctools_object_cache_set().")
 * )
 */
class CToolsObjectCacheSet extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();
    array_shift($arguments);

    if (sizeof($arguments) == 3) {
      array_pop($arguments);
    }

    return ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromScalar('user.tempstore'))
      ->appendMethodCall('set')
      ->appendArgument($arguments[0])
      ->appendArgument($arguments[1]);
  }

}
