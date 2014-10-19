<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI\EntityCreate.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "entity_create",
 *  description = @Translation("Rewrites calls to entity_create()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2266845",
 *      "title" = @Translation("<code>entity_create()</code> replaced by <code>EntityInterface::create()</code>")
 *    }
 *  },
 *  message = @Translation("<code>entity_create</code> is now a method of EntityInterface.")
 * )
 */
class EntityCreate extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    return ClassMethodCallNode::create('\Drupal', 'entityManager')
      ->appendMethodCall('getStorage')
      ->appendArgument(clone $arguments[0])
      ->appendMethodCall('create')
      ->appendArgument(clone $arguments[1]);
  }

}
