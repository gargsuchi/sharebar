<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\DBLegacyTables.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Pharborist\Functions\FunctionCallNode;
use Pharborist\StringNode;

class DBLegacyTables extends DisableFunctionCall {

  /**
   * {@inheritdoc}
   */
  public function canModify(FunctionCallNode $call) {
    $table = $call->getArguments()[0];
    // The plugin definition's tables array is defined in the deriver's configuration file,
    // and merged in by the deriver.
    return ($table instanceof StringNode && in_array($table->toValue(), $this->pluginDefinition['tables']));
  }

}
