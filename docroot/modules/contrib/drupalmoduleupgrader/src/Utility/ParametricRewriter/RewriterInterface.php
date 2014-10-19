<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\RewriterBase.
 */

namespace Drupal\drupalmoduleupgrader\Utility\ParametricRewriter;

use Drupal\Core\Executable\ExecutableInterface;

interface RewriterInterface extends ExecutableInterface {

  /**
   * Returns the function being rewristten.
   *
   * @return \Pharborist\Functions\FunctionDeclarationNode
   */
  public function getFunction();

}
