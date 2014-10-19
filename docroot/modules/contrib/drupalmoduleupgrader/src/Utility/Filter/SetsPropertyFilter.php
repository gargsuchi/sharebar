<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\Filter\SetsPropertyFilter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\Filter;

use Pharborist\Node;
use Pharborist\ObjectPropertyNode;
use Pharborist\Operator\AssignNode;

class SetsPropertyFilter extends PropertyAccessFilter {

  /**
   * Tests if the assignment sets $this->property of $this->object.
   *
   * @param \Pharborist\AssignNode $node
   *  The assignment node to test.
   *
   * @return boolean
   */
  public function __invoke(Node $node) {
    if ($node instanceof AssignNode) {
      $target = $node->getLeftOperand();
      return ($target instanceof ObjectPropertyNode && parent::__invoke($target));
    }
    else {
      return FALSE;
    }
  }

}
