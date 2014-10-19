<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI\EntityOperation.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\ObjectMethodCallNode;

/**
 * @Converter(
 *  id = "_entity_operation",
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2266845",
 *      "title" = @Translation("Entities are now classed objects implementing <code>EntityInterface</code>")
 *    }
 *  },
 *  deriver = "Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI\EntityOperationDeriver",
 * )
 */
class EntityOperation extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();
    $object = (strPos($call->getName()->getText(), 'entity_') === 0 ? $arguments[1] : $arguments[0]);

    return ObjectMethodCallNode::create(clone $object, $this->pluginDefinition['method']);
  }

}
