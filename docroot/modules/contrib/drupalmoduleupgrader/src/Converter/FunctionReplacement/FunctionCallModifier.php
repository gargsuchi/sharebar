<?php

namespace Drupal\drupalmoduleupgrader\Converter\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\Issue;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class FunctionCallModifier extends ConverterBase {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return (boolean) $this->getFunctionCalls($target)->count();
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

      foreach ($this->getFunctionCalls($target) as $call) {
        $issue->addViolation($call, $this);
      }

      $target->getReport()->addIssue($issue);
    }
  }

  /**
   * Tests if a particular function call can be modified.
   *
   * @param \Pharborist\Functions\FunctionCallNode $call
   *  The function call to test.
   *
   * @return boolean
   */
  public function canModify(FunctionCallNode $call) {
    return TRUE;
  }

  protected function getModifiableFunctionCalls(TargetInterface $target) {
    return $this->getFunctionCalls($target)->filter([ $this, 'canModify' ]);
  }

  protected function getFunctionCalls(TargetInterface $target) {
    return $target
      ->getIndexer()
      ->get('function_call', $this->pluginDefinition['function'] ?: $this->getPluginId());
  }

}
