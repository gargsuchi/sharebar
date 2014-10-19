<?php

/**
 * @file \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\EntityPropertyAccessRewriter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter;

use Drupal\Core\Entity\EntityManagerInterface;
use Pharborist\ObjectMethodCallNode;
use Pharborist\ObjectPropertyNode;
use Pharborist\Operator\AssignNode;
use Pharborist\Functions\ParameterNode;

/**
 * Base class for parametric rewriters that modify get or set operations on entities.
 */
class EntityPropertyAccessRewriter extends PropertyAccessRewriter {

  /**
   * The entity type definition of the parameter.
   *
   * @var \Drupal\Core\Entity\ContentEntityType
   */
  protected $entityInfo;

  /**
   * @var array
   */
  protected $propertyMap = [];

  public function __construct($entity_type, ParameterNode $parameter, EntityManagerInterface $entity_manager) {
    parent::__construct($parameter);

    $this->entityInfo = $entity_manager->getDefinition($entity_type);

    $this->propertyMap = [
      $this->entityInfo->getKey('id') => ['get' => 'id'],
      $this->entityInfo->getKey('bundle') => ['get' => 'bundle'],
      $this->entityInfo->getKey('label') => ['get' => 'label'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getter(ObjectPropertyNode $access) {
    $property = $access->getProperty()->__toString();

    if (isset($this->propertyMap[$property]['get'])) {
      ObjectMethodCallNode::create($access->getObject(), $this->propertyMap[$property]['get'])
        ->replaceAll($access);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function setter(ObjectPropertyNode $access, AssignNode $assignment) {
    $property = $access->getProperty()->__toString();

    if (isset($this->propertyMap[$property]['set'])) {
      ObjectMethodCallNode::create($access->getObject(), $this->propertyMap[$property]['set'])
        ->appendArgument($assignment->getRightOperand())
        ->replaceAll($assignment);
    }
  }

}
