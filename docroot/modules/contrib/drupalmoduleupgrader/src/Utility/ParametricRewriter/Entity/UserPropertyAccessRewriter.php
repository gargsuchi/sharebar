<?php

/**
 * @file \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\UserPropertyAccessRewriter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\EntityPropertyAccessRewriter;
use Pharborist\Functions\ParameterNode;

class UserPropertyAccessRewriter extends EntityPropertyAccessRewriter {

  public function __construct(ParameterNode $parameter, EntityManagerInterface $entity_manager) {
    parent::__construct('user', $parameter, $entity_manager);

    $this->propertyMap['name'] = [
      'get' => 'getUsername',
      'set' => 'setUsername',
    ];
    $this->propertyMap['mail'] = [
      'get' => 'getEmail',
      'set' => 'setEmail',
    ];
    $this->propertyMap['pass'] = [
      'get' => 'getPassword',
      'set' => 'setPassword',
    ];
    $this->propertyMap['is_new']['get'] = 'isNew';
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $this->getParameter()->setTypeHint('Drupal\\user\\UserInterface');
    parent::execute();
  }

}
