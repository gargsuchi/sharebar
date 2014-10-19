<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookURLOutboundAlter.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Converter(
 *  id = "HookURLOutboundAlter",
 *  description = @Translation("Converts hook_url_outbound_alter() to a service."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2238759",
 *      "title" = @Translation("<code>hook_url_outbound_alter()</code> replaced by <code>OutboundPathProcessorInterface</code>")
 *    }
 *  },
 *  message = @Translation("<code>hook_url_outbound_alter()</code> has been replaced by path processors."),
 *  hook = "url_outbound_alter"
 * )
 */
class HookURLOutboundAlter extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $target->getServices()->set('outbound_path_processor', [
      'class' => 'Drupal\\' . $target->id() . '\\OutboundPathProcessor',
      'tags' => [
        [ 'name' => 'path_processor_outbound' ],
      ],
    ]);
    $this->writeInfo($target, 'services', [ 'services' => $target->getServices()->toArray() ]);

    $render = [
      '#theme' => 'dmu_outbound_path_processor',
      '#module' => $target->id(),
    ];
    $processor = $this->parse($render);
    $target
      ->getIndexer()
      ->get('hook', 'url_outbound_alter')
      ->get(0)
      ->cloneAsMethodOf($processor)
      ->setName('processOutbound');
    $this->writeClass($target, $processor);
  }

}
