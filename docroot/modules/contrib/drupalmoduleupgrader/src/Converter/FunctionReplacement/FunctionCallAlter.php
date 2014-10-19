<?php

namespace Drupal\drupalmoduleupgrader\Converter\FunctionReplacement;

use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;

abstract class FunctionCallAlter extends FunctionCallModifier {

  /**
   * Alters a function call in-place. This should *not* detach the call from
   * its syntax tree -- for that, use a rewriter.
   *
   * @param \Pharborist\Functions\FunctionCallNode $call
   *  The function call to alter.
   */
  abstract public function alter(FunctionCallNode $call);

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    foreach ($this->getFunctionCalls($target) as $call) {
      /** @var \Pharborist\Functions\FunctionCallNode $call */
      if ($this->canModify($call)) {
        $this->alter($call);
      }
      elseif ($this->pluginDefinition['require_alter'] == TRUE) {
        $statement = $call->closest(Filter::isInstanceOf('\Pharborist\StatementNode'));
        $comment = $statement->toComment();
        $statement->replaceWith($comment);
      }
    }
    $target->getCodeManager()->saveAll();
  }

}
