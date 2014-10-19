<?php

namespace Drupal\drupalmoduleupgrader\Indexer;

use Drupal\drupalmoduleupgrader\TargetInterface;

interface IndexerFactoryInterface {

  /**
   * Creates an indexer for a single target module.
   *
   * @param \Drupal\drupalmoduleupgrader\TargetInterface $target
   *
   * @return \Drupal\drupalmoduleupgrader\Indexer\IndexerInterface
   */
  public function index(TargetInterface $target);

}
