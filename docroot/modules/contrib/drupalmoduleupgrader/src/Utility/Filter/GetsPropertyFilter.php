<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\Filter\GetsPropertyFilter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\Filter;

use Pharborist\ObjectPropertyNode;
use Pharborist\Operator\AssignNode;

class GetsPropertyFilter extends PropertyAccessFilter {

  /**
   * Tests if the assignment gets $this->property of $this->method.
   *
   * @param \Pharborist\AssignNode $node
   *  The assignment node to test.
   *
   * @return boolean
   */
  public function __invoke(AssignNode $node) {
    $target = $this->getRightOperand();
    return ($target instanceof ObjectPropertyNode && parent::__invoke($target));
  }

}
