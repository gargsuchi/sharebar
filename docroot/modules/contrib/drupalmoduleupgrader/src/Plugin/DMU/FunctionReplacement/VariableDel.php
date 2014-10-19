<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\VariableDel.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "variable_del",
 *  description = @Translation("Replaces variable_del() calls with Configuration API."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2183531",
 *      "title" = @Translation("The Variable API has been removed")
 *    }
 *  },
 *  message = @Translation("<code>variable_del()</code> has been removed.")
 * )
 */
class VariableDel extends VariableBase {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    return parent::rewrite($call)
      ->appendMethodCall('clear')
      ->appendArgument($call->getArguments()[0]);
  }

}
