<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\DisableFunctionCall.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallModifier;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\LineCommentBlockNode;

/**
 * @Converter(
 *  id = "_disable",
 *  description = @Translation("Disables any function call by commenting it out."),
 *  deriver = "Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\DisableDeriver"
 * )
 */
class DisableFunctionCall extends FunctionCallModifier {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    foreach ($this->getModifiableFunctionCalls($target) as $call) {
      /** @var \Pharborist\Functions\FunctionCallNode $call */
      /** @var \Pharborist\StatementNode $statement */
      $statement = $call->closest(Filter::isInstanceOf('\Pharborist\StatementNode'));
      $comment = $statement->toComment();
      $statement->replaceWith($comment);

      if (isset($this->pluginDefinition['fixme'])) {
        $fixme = LineCommentBlockNode::create('@FIXME: ' . $this->pluginDefinition['fixme']);
        $fixme->insertBefore($comment);
      }
    }
  }

}
