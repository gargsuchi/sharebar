<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookInit.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * @Converter(
 *  id = "HookInit",
 *  description = @Translation("Converts Drupal 7's hook_init() to an EventSubscriber."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2013014",
 *      "title" = @Translation("<code>hook_init()</code> removed")
 *    }
 *  },
 *  message = @Translation("hook_init() has been removed in Drupal 8 and replaced by an event subscription system."),
 *  hook = "init"
 * )
 */
class HookInit extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $target->getServices()->set('init_subscriber', [
      'class' => 'Drupal\\' . $target->id() . '\\EventSubscriber\\InitSubscriber',
      'tags' => [
        [ 'name' => 'event_subscriber' ],
      ],
    ]);
    $this->writeInfo($target, 'services', [ 'services' => $target->getServices()->toArray() ]);

    $render = [
      '#theme' => 'dmu_event_subscriber',
      '#module' => $target->id(),
      '#class' => 'InitSubscriber',
      '#event' => 'KernelEvents::REQUEST',
    ];
    $subscriber = $this->parse($render);
    $target->getIndexer()->get('hook', 'init')->get(0)->cloneAsMethodOf($subscriber)->setName('onEvent');
    $this->writeClass($target, $subscriber);
  }

}
