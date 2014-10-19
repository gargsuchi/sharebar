<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\Filter\ContainsLogicFilter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\Filter;

use Pharborist\ClassMethodNode;
use Pharborist\Filter;
use Pharborist\Functions\FunctionDeclarationNode;

class ContainsLogicFilter {

  /**
   * Tests if a function contains logic: any branching operator, function
   * call, or object instantiation.
   *
   * @param \Pharborist\Functions\FunctionDeclarationNode|\Pharborist\ClassMethodNode $node
   *  The function (or method) to test.
   *
   * @return boolean
   */
  public function __invoke($node) {
    if ($node instanceof FunctionDeclarationNode || $node instanceof ClassMethodNode) {
      $logic = $node->find(Filter::isInstanceOf('\Pharborist\IfNode', '\Pharborist\SwitchNode', '\Pharborist\CallNode', '\Pharborist\NewNode'));
      return (! $logic->isEmpty());
    }
    else {
      return FALSE;
    }
  }

}
