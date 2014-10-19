<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityLoad.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ArrayNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\FalseNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;
use Pharborist\NullNode;
use Pharborist\StringNode;

/**
 * @Converter(
 *  id = "entity_load",
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2266845",
 *      "title" = @Translation("Entities are now classed objects implementing <code>EntityInterface</code>")
 *    }
 *  },
 *  deriver = "Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityLoadDeriver"
 * )
 */
class EntityLoad extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    if (sizeof($arguments) == 2) {
      $entity_type = clone $arguments[0];
      $entity_id = clone $arguments[1];
    }
    else {
      // The entity_type key is added to the plugin definition by the deriver, if the
      // function being handled is node_load(), user_load(), or similar.
      $entity_type = StringNode::fromValue($this->pluginDefinition['entity_type']);
      $entity_id = clone $arguments[0];
    }

    // entity_load('node', FALSE) => \Drupal::entityManager()->getStorage('node')->loadMultiple()
    // entity_load('node', 30) => \Drupal::entityManager()->getStorage('node')->load(30)
    // entity_load('node', [28, 29, 30]) => \Drupal::entityManager()->getStorage('node')->loadMultiple([28, 29, 30])
    return ClassMethodCallNode::create('\Drupal', 'entityManager')
      ->appendMethodCall('getStorage')
      ->appendArgument($entity_type)
      ->appendMethodCall($entity_id instanceof ArrayNode || $entity_id instanceof FalseNode ? 'loadMultiple' : 'load')
      ->appendArgument($entity_id instanceof FalseNode ? NullNode::create('NULL') : $entity_id);
  }

}
