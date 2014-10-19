<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\ParametricRewriter\PropertyAccessRewriter.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter;

use Drupal\drupalmoduleupgrader\Utility\Filter\AssignmentFilter;
use Drupal\drupalmoduleupgrader\Utility\Filter\PropertyAccessFilter;
use Drupal\drupalmoduleupgrader\Utility\Filter\SetsPropertyFilter;
use Drupal\drupalmoduleupgrader\Utility\Filter\UsesPropertyFilter;
use Pharborist\Filter;
use Pharborist\ObjectPropertyNode;
use Pharborist\Operator\AssignNode;

/**
 * Base class for parametric rewriters that rewrite property accesses.
 */
abstract class PropertyAccessRewriter extends ParametricRewriterBase {

  /**
   * Rewrites a property get operation.
   *
   * @param \Pharborist\ObjectPropertyNode $access
   *  The object property access, e.g., $node->nid.
   */
  abstract protected function getter(ObjectPropertyNode $access);

  /**
   * Rewrites a property set operation.
   *
   * @param \Pharborist\ObjectPropertyNode $access
   *  The object property access, e.g., $node->nid.
   * @param \Pharborist\AssignNode $assignment
   *  The full assignment expression the operation is part of.
   */
  abstract protected function setter(ObjectPropertyNode $access, AssignNode $assignment);

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $function = $this->getFunction();
    $variable = $this->getParameter()->getName();

    $reassignments = $function
      ->find(Filter::isInstanceOf('\Pharborist\AssignNode'))
      ->filter(new AssignmentFilter($variable));

    if (sizeof($reassignments) > 0) {
      throw new \LogicException('Cannot parametrically rewrite a function with parameter reassignment.');
    }

    $is_setter = new SetsPropertyFilter($variable);

    $accesses = $function
      ->find(Filter::isInstanceOf('\Pharborist\ObjectPropertyNode'))
      ->filter(new PropertyAccessFilter($variable));

    foreach ($accesses as $access) {
      $assignment = $access->closest(Filter::isInstanceOf('\Pharborist\Operator\AssignNode'));

      if ($assignment && $is_setter($assignment)) {
        $this->setter($access, $assignment);
      }
      else {
        $this->getter($access);
      }
    }
  }

}
