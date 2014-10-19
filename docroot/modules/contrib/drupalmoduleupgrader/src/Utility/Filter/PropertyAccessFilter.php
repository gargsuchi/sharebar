<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\Filter\PropertyAccessFilter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\Filter;

use Pharborist\Node;
use Pharborist\ObjectPropertyNode;

class PropertyAccessFilter {

  /**
   * @var string
   */
  protected $object;

  /**
   * @var string
   */
  protected $property;

  /**
   * Constructs a UsesPropertyFilter.
   */
  public function __construct($object, $property = NULL) {
    $this->object = $object;
    $this->property = $property;
  }

  /**
   * Tests if the node accesses $this->object, optionally also checking if it
   * accesses $this->property.
   *
   * @param \Pharborist\ObjectPropertyNode $node
   *  The $object->property node to test.
   *
   * @return boolean
   */
  public function __invoke(Node $node) {
    if ($node instanceof ObjectPropertyNode && $node->getObject()->getName() == $this->object) {
      return ($this->property ? $node->getProperty()->__toString() == $this->property : TRUE);
    }
    else {
      return FALSE;
    }
  }

}
