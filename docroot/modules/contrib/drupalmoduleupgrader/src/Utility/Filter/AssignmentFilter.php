<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\Filter\AssignmentFilter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\Filter;

use Pharborist\Operator\AssignNode;
use Pharborist\VariableNode;

class AssignmentFilter {

  /**
   * @var string
   */
  private $variable;

  /**
   * Constructs an AssignmentFilter.
   */
  public function __construct($variable) {
    $this->variable = ltrim($variable, '$');
  }

  /**
   * Tests if the assignment fully reassigns $this->variable.
   *
   * @param \Pharborist\AssignNode $node
   *  The assignment to test.
   *
   * @return boolean
   */
  public function __invoke(AssignNode $node) {
    $assignee = $node->getLeftOperand();
    return ($assignee instanceof VariableNode && $variable->getName() == $this->variable);
  }

}
