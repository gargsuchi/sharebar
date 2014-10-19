<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Converter\HookConverterBase.
 */

namespace Drupal\drupalmoduleupgrader\Converter;

use Drupal\drupalmoduleupgrader\Issue;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Drupal\drupalmoduleupgrader\Utility\Filter\ContainsLogicFilter;
use Pharborist\DocCommentNode;

/**
 * Base class to convert hooks.
 */
abstract class HookConverterBase extends ConverterBase {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    $indexer = $target->getIndexer();

    $hook = $this->pluginDefinition['hook'];
    if ($indexer->has('hook', $hook)) {
      return (! $indexer->get('hook', $hook)->get(0)->is(new ContainsLogicFilter));
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    if (isset($this->pluginDefinition['message'])) {
      $issue = new Issue($target, $this->pluginDefinition['message']);

      foreach ($this->pluginDefinition['documentation'] as $doc) {
        $issue->addDocumentation($doc['url'], $doc['title']);
      }
      $issue->addViolation($target->getIndexer()->get('hook', $this->pluginDefinition['hook'])->get(0), $this);

      $target->getReport()->addIssue($issue);
    }
  }

  /**
   * Sets a @FIXME notice above the hook implementation. The notice's text is
   * pulled from the plugin definition.
   */
  protected function addFixMe() {
    if ($this->pluginDefinition['fixme']) {
      $comment = $this->hook->getDocComment();
      $comment_text = $comment ? $comment->getCommentText() : 'Implements hook_' . $this->pluginDefinition['hook'] . '().';
      $this->hook->setDocComment(DocCommentNode::create("{$comment_text}\n\n@FIXME\n" . $this->pluginDefinition['fixme']));
    }
  }

}
