<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\FieldInfoFieldTypes.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;

/**
 * @Converter(
 *  id = "field_info_field_types",
 *  description = @Translation("Rewrites calls to field_info_field_types()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2064123",
 *      "title" = @Translation("Field types are now plugins"),
 *    }
 *  },
 *  message = @Translation("field_info_field_types() has been replaced with a field type plugin manager.")
 * )
 */
class FieldInfoFieldTypes extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $replacement = ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromValue('plugin.manager.field.field_type'));

    $arguments = $call->getArguments();
    if (empty($arguments)) {
      return $replacement->appendMethodCall('getDefinitions');
    }
    elseif (sizeof($arguments) == 1) {
      return $replacement
        ->appendMethodCall('getDefinition')
        ->appendArgument(clone $arguments[0]);
    }
  }

}
