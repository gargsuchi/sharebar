<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language\LocaleLanguageSwitcherURL.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;

/**
 * @Converter(
 *  id = "locale_language_switcher_url",
 *  description = @Translation("Rewrites calls to locale_language_switcher_url()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2174591",
 *      "title" = @Translation("<code>locale_language_switcher_url()</code> is now a method of the <var>language-url</var> language negotiator")
 *    }
 *  },
 *  message = @Translation("<code>locale_language_switcher_url()</code> is now a method of a language negotiation plugin.")
 * )
 */
class LocaleLanguageSwitcherURL extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $replacement = ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromScalar('plugin.manager.language_negotiation_method'))
      ->appendMethodCall('createInstance')
      ->appendArgument('language-url')
      ->appendMethodCall('getLanguageSwitchLinks')
      ->appendArgument(ClassMethodCallNode::create('\Drupal', 'request'));

    foreach ($call->getArguments() as $argument) {
      $replacement->appendArgument($argument);
    }

    return $replacement;
  }

}
