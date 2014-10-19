<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\FunctionReplacement.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\Converter\IndexerInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "FunctionReplacement",
 *  description = @Translation("Rewrites function calls that have changed in Drupal 8."),
 *  delegator = "plugin.manager.drupalmoduleupgrader.function"
 * )
 */
class FunctionReplacement extends ConverterBase implements IndexerInterface {

  /**
   * Every function call in the module being converted.
   *
   * @var array
   */
  private $functions = [];

  /**
   * Plugin IDs for every available and applicable delegate.
   *
   * @var string[]
   */
  private $delegateIDs = [];

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->delegateIDs = array_keys($this->delegator->getDefinitions());
  }

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function index(TargetInterface $target) {
    $indexer = $target->getIndexer();

    foreach ($target->getFinder() as $file) {
      /** @var \SplFileInfo $file */
      $target
        ->getCodeManager()
        ->open($file->getPathname())
        ->find(Filter::isInstanceOf('\Pharborist\Functions\FunctionCallNode'))
        ->each(function(FunctionCallNode $call) use ($file, $indexer) {
          $indexer->add('function_call', $call->getName()->getText(), $file->getPathname());
        });
    }
  }

  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    foreach ($this->delegateIDs as $plugin_id) {
      $plugin = $this->getDelegate($plugin_id);

      if ($plugin->isExecutable($target)) {
        $plugin->analyze($target);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    foreach ($this->delegateIDs as $plugin_id) {
      $plugin = $this->getDelegate($plugin_id);

      if ($plugin->isExecutable($target)) {
        $plugin->convert($target);
      }
    }
    $target->getCodeManager()->saveAll();
  }

}
