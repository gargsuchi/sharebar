<?php

namespace Drupal\drupalmoduleupgrader\Indexer\Database;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Database\Connection as DatabaseConnection;
use Drupal\drupalmoduleupgrader\Indexer\IndexerFactoryInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;

class IndexerFactory implements IndexerFactoryInterface {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $pluginManager;

  /**
   * Constructs an IndexerFactory.
   *
   * @param \Drupal\Core\Database\Connection $db
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   */
  public function __construct(DatabaseConnection $db, PluginManagerInterface $plugin_manager) {
    $this->db = $db;
    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function index(TargetInterface $target) {
    $indexer = new Indexer($target, $this->db, $this->pluginManager);
    $indexer->build();
    return $indexer;
  }

}
