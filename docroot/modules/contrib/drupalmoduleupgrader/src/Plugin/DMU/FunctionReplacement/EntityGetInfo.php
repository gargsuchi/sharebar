<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityGetInfo.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "entity_get_info",
 *  description = @Translation("Rewrites calls to entity_get_info()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1929006",
 *      "title" = @Translation("<code>entity_get_info()</code> is deprecated")
 *    }
 *  },
 *  message = @Translation("<code>entity_get_info()</code> is now a method of the EntityManager service.")
 * )
 */
class EntityGetInfo extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $manager = ClassMethodCallNode::create('\Drupal', 'entityManager');

    $arguments = $call->getArguments();
    if (empty($arguments)) {
      return $manager->appendMethodCall('getDefinitions');
    }
    elseif (sizeof($arguments) == 1) {
      return $manager
        ->appendMethodCall('getDefinition')
        ->appendArgument(clone $arguments[0]);
    }
  }

}
