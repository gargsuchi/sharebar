<?php

namespace Drupal\drupalmoduleupgrader\Indexer\Memory;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\drupalmoduleupgrader\Indexer\IndexerFactoryInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;

class IndexerFactory implements IndexerFactoryInterface {

  /**
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $pluginManager;

  /**
   * Constructs an IndexerFactory.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   */
  public function __construct(PluginManagerInterface $plugin_manager) {
    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function index(TargetInterface $target) {
    $indexer = new Indexer($target, $this->pluginManager);
    $indexer->build();
    return $indexer;
  }

}
