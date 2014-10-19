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
 *  id = "field_info_widget_types",
 *  description = @Translation("Rewrites calls to field_info_widget_types()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1796000",
 *      "title" = @Translation("Field widgets are now plugins"),
 *    }
 *  },
 *  message = @Translation("field_info_widget_types() has been replaced with a field widget plugin manager.")
 * )
 */
class FieldInfoWidgetTypes extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $replacement = ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromValue('plugin.manager.field.widget'));

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
