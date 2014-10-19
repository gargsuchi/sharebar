<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\VariableSet.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "variable_set",
 *  description = @Translation("Replaces variable_set() calls with Configuration API."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2183531",
 *      "title" = @Translation("The Variable API has been removed")
 *    }
 *  },
 *  message = @Translation("<code>variable_set()</code> has been removed.")
 * )
 */
class VariableSet extends VariableBase {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    /** @var \Pharborist\ObjectMethodCallNode $config */
    $config = parent::rewrite($call)->appendMethodCall('set');

    $arguments = $call->getArguments();
    $config->appendArgument(clone $arguments[0]);
    $config->appendArgument(clone $arguments[1]);

    return $config->appendMethodCall('save');
  }

}
