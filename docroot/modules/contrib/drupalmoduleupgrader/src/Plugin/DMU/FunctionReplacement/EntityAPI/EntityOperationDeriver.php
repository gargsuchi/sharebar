<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI\EntityOperationDeriver.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityAPI;

use Drupal\Component\Plugin\Derivative\DeriverInterface;

/**
 * Builds derivative definitions for the _entity_operation plugin, allowing us to
 * rewrite calls to things like entity_save(), node_delete(), entity_label(), etc.
 */
class EntityOperationDeriver implements DeriverInterface {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinition($derivative_id, $base_definition) {
    $derivatives = $this->getDerivativeDefinitions($base_definition);

    if (isset($derivatives[$derivative_id])) {
      return $derivatives[$derivative_id];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_definition) {
    $derivatives = [];

    $config = \Drupal::config('drupalmoduleupgrader.entity_operations')->get();
    foreach ($config as $entity_type => $operations) {
      foreach ($operations as $operation) {
        $function = $entity_type . '_' . $operation;
        $derivatives[$function] = $base_definition + [
          'function' => $function,
          'method' => $operation,
          'message' => '<code>' . $function . '()</code> is now <code>EntityInterface::' . $operation . '()</code>.',
          'description' => 'Rewrites calls to ' . $function . '()',
        ];
      }
    }

    return $derivatives;
  }

}
