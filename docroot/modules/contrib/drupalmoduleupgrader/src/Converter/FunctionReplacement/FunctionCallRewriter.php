<?php

namespace Drupal\drupalmoduleupgrader\Converter\FunctionReplacement;

use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\LineCommentBlockNode;

abstract class FunctionCallRewriter extends FunctionCallModifier {

  protected $target;

  /**
   * Generates a replacement for a function call.
   *
   * @param \Pharborist\Functions\FunctionCallNode $call
   *  The original function call, which will be discarded and completely
   *  replaced by whatever is returned.
   *
   * @return \Pharborist\Node
   */
  abstract public function rewrite(FunctionCallNode $call);

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $this->target = $target;

    foreach ($this->getFunctionCalls($target) as $call) {
      /** @var \Pharborist\Functions\FunctionCallNode $call */
      if ($this->canModify($call)) {
        $rewritten = $this->rewrite($call);
        $call->replaceWith($rewritten);
      }
      elseif ($this->pluginDefinition['require_rewrite'] == TRUE) {
        $statement = $call->closest(Filter::isInstanceOf('\Pharborist\StatementNode'));
        $comment = $statement->toComment();
        $statement->replaceWith($comment);

        if (isset($this->pluginDefinition['fixme'])) {
          LineCommentBlockNode::create($this->pluginDefinition['fixme'])->insertBefore($comment);
        }
      }
    }
    $target->getCodeManager()->saveAll();
  }

}
