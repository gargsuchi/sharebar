<?php

/**
 * @file \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\NodePropertyAccessRewriter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\EntityPropertyAccessRewriter;
use Pharborist\Functions\ParameterNode;

class NodePropertyAccessRewriter extends EntityPropertyAccessRewriter {

  public function __construct(ParameterNode $parameter, EntityManagerInterface $entity_manager) {
    parent::__construct('node', $parameter, $entity_manager);

    $this->propertyMap['sticky'] = [
      'get' => 'isSticky',
      'set' => 'setSticky',
    ];
    $this->propertyMap['status'] = [
      'get' => 'isPublished',
      'set' => 'setPublished',
    ];
    $this->propertyMap['promoted'] = [
      'get' => 'isPromoted',
      'set' => 'setPromoted',
    ];
    $this->propertyMap['title'] = [
      'get' => 'getTitle',
      'set' => 'setTitle',
    ];
    $this->propertyMap['uid'] = [
      'get' => 'getOwnerId',
      'set' => 'setOwnerId',
    ];
    $this->propertyMap['created'] = [
      'get' => 'getCreatedTime',
      'set' => 'setCreatedTime',
    ];
    $this->propertyMap['is_new']['get'] = 'isNew';
    $this->propertyMap['type']['get'] = 'getType';
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $this->getParameter()->setTypeHint('Drupal\\node\\NodeInterface');
    parent::execute();
  }

}
