<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookPermission.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @Converter(
 *  id = "HookPermission",
 *  description = @Translation("Converts static implementations of hook_permission() to YAML."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2311427",
 *      "title" = @Translation("Defining permissions in <kbd>MODULE.permissions.yml</kbd>")
 *    }
 *  },
 *  hook = "permission",
 *  message = @Translation("Static permissions are now defined in <kbd>MODULE.permissions.yml</kbd>.")
 * )
 */
class HookPermission extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $hook = $target->getIndexer()->get('hook', 'permission')->get(0);
    eval($hook->getText());
    $this->writeInfo($target, 'permissions', call_user_func($hook->getName()->getText()));
  }

}
