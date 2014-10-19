<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Utility\FunctionAnalyzerTrait.
 */

namespace Drupal\drupalmoduleupgrader\Utility;

use Pharborist\CallNode;
use Pharborist\NewNode;
use Pharborist\Node;
use Pharborist\IfNode;
use Pharborist\Functions\ParameterNode;
use Pharborist\SwitchNode;
use Pharborist\Functions\FunctionDeclarationNode;

/**
 * Contains helper methods for function analysis.
 */
trait FunctionAnalyzerTrait {

  /**
   * Checks if the function contains logic, which includes if statements, switch
   * statements, function calls, or the 'new' operator.
   *
   * @param \Pharborist\Functions\FunctionDeclarationNode $function
   *  The function to check.
   *
   * @return boolean
   */
  public function containsLogic(FunctionDeclarationNode $function) {
    $filter = function (Node $node) {
      return ($node instanceof IfNode || $node instanceof SwitchNode || $node instanceof CallNode || $node instanceof NewNode);
    };

    return (boolean) sizeof($function->children($filter));
  }

  /**
   * Tests if the function has any optional parameters (i.e., with a default value).
   *
   * @param \Pharborist\Functions\FunctionDeclarationNode $function
   *  The function to test.
   *
   * @return boolean
   */
  public function requiresArguments(FunctionDeclarationNode $function) {
    foreach ($function->getParameters() as $parameter) {
      if ($parameter->getValue()) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Returns the required parameters of a function. This is a stopgap until
   * Pharborist issue #45 is fixed.
   *
   * @param \Pharborist\Functions\FunctionDeclarationNode $function
   *  The function declaration.
   *
   * @return array
   */
  public function getRequiredParameters(FunctionDeclarationNode $function) {
    return array_filter($function->getParameters(), function(ParameterNode $parameter) {
      $value = $parameter->getValue();
      return empty($value);
    });
  }

}
