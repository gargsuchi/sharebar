<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\VariableBase.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;
use Pharborist\StringNode;

/**
 * Base class for Variable API replacements.
 */
abstract class VariableBase extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    // Create the basic call to \Drupal::config('MODULE.settings')
    $config = ClassMethodCallNode::create('\Drupal', 'config');
    $config->appendArgument(StringNode::fromValue($this->target->id() . '.settings'));

    return $config;
  }

}
