<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\DisableDeriver.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\Component\Plugin\Derivative\DeriverInterface;

/**
 * Builds derivative definitions for the _disable plugin, based on a bundled configuration
 * file. This allows us (plugin authors) to easily define which function calls can be
 * commented out.
 */
class DisableDeriver implements DeriverInterface {

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

    $config = \Drupal::config('drupalmoduleupgrader.disabled_functions')->get();
    foreach ($config as $key => $info) {
      // $key can either be the name of a single function, or an arbitrary string
      // identifying a group of functions to handle.
      if (empty($info['functions'])) {
        $info['functions'] = [$key];
      }

      foreach ($info['functions'] as $function) {
        $merge_definition = $info + [
          'issue_type' => $key,
          'function' => $function,
        ];
        unset($merge_definition['functions']);
        $derivatives[$function] = array_merge($base_definition, $merge_definition);
      }
    }

    return $derivatives;
  }

}
