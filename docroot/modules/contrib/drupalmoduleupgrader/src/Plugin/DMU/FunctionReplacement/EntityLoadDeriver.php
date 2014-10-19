<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityLoadDeriver.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\Component\Plugin\Derivative\DeriverInterface;

/**
 * Builds derivative definitions for the entity_load plugin, allowing it to handle things
 * like node_load(), node_load_multiple(), etc.
 */
class EntityLoadDeriver implements DeriverInterface {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinition($derivative_id, $base_definition) {
    $derivatives = $this->getDerivativeDefinitions($base_definition);

    if (isset($derivatives[$derivative_id])) {
      $derivative = $derivatives[$derivative_id];

      $function = $derivative['function'];
      if (preg_match('/_load_multiple$/', $function)) {
        $derivative['message'] = '<code>' . $function . '()</code> is now <code>EntityInterface::loadMultiple()</code>.';
      }
      else {
        $derivative['message'] = '<code>' . $function . '()</code> is now <code>EntityInterface::load()</code>.';
      }
      $derivative['description'] = 'Rewrites calls to ' . $function . '().';

      return $derivative;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_definition) {
    $derivatives = [];

    foreach (['node', 'user', 'comment', 'taxonomy_term'] as $entity_type) {
      $function = $entity_type . '_load';
      $derivatives[$function] = $base_definition + [
        'function' => $function,
        'entity_type' => $entity_type,
      ];

      $function = $entity_type . '_load_multiple';
      $derivatives[$function] = $base_definition + [
        'function' => $function,
        'entity_type' => $entity_type,
      ];
    }

    $derivatives['entity_load'] = $base_definition + [
      'function' => 'entity_load',
    ];
    $derivatives['entity_load_multiple'] = $base_definition + [
      'function' => 'entity_load_multiple',
    ];

    return $derivatives;
  }

}
