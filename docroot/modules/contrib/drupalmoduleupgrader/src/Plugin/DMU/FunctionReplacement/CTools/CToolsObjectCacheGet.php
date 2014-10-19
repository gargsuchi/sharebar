<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\CTools\CToolsObjectCacheGet.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\CTools;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;

/**
 * @Converter(
 *  id = "ctools_object_cache_get",
 *  description = @Translation("Converts calls to ctools_object_cache_get()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1805940",
 *      "title" = @Translation("TempStore API added to core")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2164623",
 *      "title" = @Translation("Modules added to Drupal 8 core")
 *    }
 *  },
 *  message = @Translation("The CTools object caching system has been moved into Drupal core.")
 * )
 */
class CToolsObjectCacheGet extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    return ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromScalar('user.tempstore'))
      ->appendMethodCall('get')
      ->appendArgument(clone $call->getArguments()[1]);
  }

}
