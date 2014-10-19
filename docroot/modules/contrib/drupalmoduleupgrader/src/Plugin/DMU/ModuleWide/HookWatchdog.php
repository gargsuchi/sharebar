<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookWatchdog.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;

/**
 * @Converter(
 *  id = "HookWatchdog",
 *  description = @Translation("Converts hook_watchdog() to an implementation of \\Psr\\Log\\LoggerInterface."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2270941",
 *      "title" = @Translation("<code>hook_watchdog()</code> removed; <code>watchdog()</code> deprecated and replaced by PSR-3 compliant logging service")
 *    }
 *  },
 *  message = @Translation("<code>hook_watchdog()</code> has been removed."),
 *  hook = "watchdog"
 * )
 */
class HookWatchdog extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $target->getServices()->set('default_logger', [
      'class' => 'Drupal\\' . $target->id() . '\\Logger\\DefaultLogger',
      'tags' => [
        [ 'name' => 'logger' ],
      ],
    ]);
    $this->writeInfo($target, 'services', [ 'services' => $target->getServices()->toArray() ]);

    $render = [
      '#theme' => 'dmu_logger',
      '#module' => $target->id(),
    ];
    $this->writeClass($target, $this->parse($render));
  }

}
