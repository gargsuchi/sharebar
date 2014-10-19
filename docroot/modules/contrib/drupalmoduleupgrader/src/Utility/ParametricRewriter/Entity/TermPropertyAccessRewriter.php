<?php

/**
 * @file \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\TermPropertyAccessRewriter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\EntityPropertyAccessRewriter;
use Pharborist\Functions\ParameterNode;

class TermPropertyAccessRewriter extends EntityPropertyAccessRewriter {

  public function __construct(ParameterNode $parameter, EntityManagerInterface $entity_manager) {
    parent::__construct('taxonomy_term', $parameter, $entity_manager);
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $this->getParameter()->setTypeHint('Drupal\\taxonomy\\TermInterface');
    parent::execute();
  }

}
