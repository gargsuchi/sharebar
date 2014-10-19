<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\RewriterBase.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter;

use Pharborist\Functions\FunctionDeclarationNode;

/**
 * Base class for parametric rewriters.
 */
class RewriterBase {

  /**
   * @var string
   */
  protected $variable;

  /**
   * @var \Pharborist\Functions\FunctionDeclarationNode
   */
  protected $function;

  public function __construct($variable, FunctionDeclarationNode $function) {
    $this->variable = $variable;
    $this->function = $function;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunction() {
    return $this->function;
  }

}
