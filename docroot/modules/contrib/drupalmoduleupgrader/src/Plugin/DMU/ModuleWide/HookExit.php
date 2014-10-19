<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookExit.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * @Converter(
 *  id = "HookExit",
 *  description = @Translation("Converts Drupal 7's hook_exit() to an EventSubscriber."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1911186",
 *      "title" = @Translation("<code>hook_exit()</code> replaced by event subscriber")
 *    }
 *  },
 *  message = @Translation("<code>hook_exit()</code> has been removed."),
 *  hook = "exit"
 * )
 */
class HookExit extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $target->getServices()->set('exit_subscriber', [
      'class' => 'Drupal\\' . $target->id() . '\\EventSubscriber\\ExitSubscriber',
      'tags' => [
        [ 'name' => 'event_subscriber' ],
      ],
    ]);
    $this->writeInfo($target, 'services', [ 'services' => $target->getServices()->toArray() ]);

    $render = [
      '#theme' => 'dmu_event_subscriber',
      '#module' => $target->id(),
      '#class' => 'ExitSubscriber',
      '#event' => 'KernelEvents::TERMINATE',
    ];
    $subscriber = $this->parse($render);
    $target->getIndexer()->get('hook', 'exit')->get(0)->cloneAsMethodOf($subscriber)->setName('onEvent');
    $this->writeClass($target, $subscriber);
  }

}
