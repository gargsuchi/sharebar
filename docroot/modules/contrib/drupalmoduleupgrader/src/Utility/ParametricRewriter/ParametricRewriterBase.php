<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\ParametricRewriterBase.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter;

use Pharborist\Functions\ParameterNode;

/**
 * Base class for parametric rewriters.
 */
class ParametricRewriterBase extends RewriterBase {

  /**
   * The function parameter.
   *
   * @var \Pharborist\Functions\ParameterNode
   */
  protected $parameter;

  /**
   * {@inheritdoc}
   */
  public function __construct(ParameterNode $parameter) {
    $this->parameter = $parameter;
    parent::__construct($parameter->getName(), $parameter->getFunction());
  }

  /**
   * {@inheritdoc}
   */
  public function getParameter() {
    return $this->parameter;
  }

  /**
   * Changes the parameter's name and type hint to match the given parameter.
   *
   * @param \Pharborist\Functions\ParameterNode $that
   *  The parameter to match.
   */
  public function match(ParameterNode $that) {
    $name = $that->getName();
    if ($this->parameter->getName() !== $name) {
      $this->parameter->setName($name, TRUE);
    }

    $type_hint = $that->getTypeHint();
    if ($type_hint) {
      $this->parameter->setTypeHint(clone $type_hint);
    }
  }

}
