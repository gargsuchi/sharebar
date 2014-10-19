<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookUninstall.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\LineCommentBlockNode;
use Pharborist\NodeCollection;

/**
 * @Converter(
 *  id = "HookUninstall",
 *  description = @Translation("Removes variable_del() calls from hook_uninstall()."),
 *  message = @Translation("Uninstalling default configuration is handled automatically by core."),
 *  hook = "uninstall"
 * )
 */
class HookUninstall extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return (boolean) $target
      ->getIndexer()
      ->get('hook', 'uninstall')
      ->get(0)
      ->find(Filter::isFunctionCall('variable_del'))
      ->count();
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $function = $target->getIndexer()->get('hook', 'uninstall')->get(0);

    $function
      ->find(Filter::isFunctionCall('variable_del'))
      ->each(function(FunctionCallNode $call) {
        /** @var \Pharborist\StatementNode $statement */
        $statement = $call->closest(Filter::isInstanceOf('\Pharborist\StatementNode'));
        $comment = $statement->toComment();
        $statement->replaceWith($comment);
        LineCommentBlockNode::create('You no longer need to delete module variables during uninstall; this is handled automatically.')->insertBefore($comment);
      });

    $target->getCodeManager()->save($function);
  }

}
