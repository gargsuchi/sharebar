<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language\LocaleLanguageSwitcherSession.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;

/**
 * @Converter(
 *  id = "locale_language_switcher_session",
 *  description = @Translation("Rewrites calls to locale_language_switcher_session()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2174591",
 *      "title" = @Translation("<code>locale_language_switcher_session()</code> is now a method of the <var>language-session</var> language negotiator")
 *    }
 *  },
 *  message = @Translation("<code>locale_language_switcher_session()</code> is now a method of a language negotiation plugin.")
 * )
 */
class LocaleLanguageSwitcherSession extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $replacement = ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromScalar('plugin.manager.language_negotiation_method'))
      ->appendMethodCall('createInstance')
      ->appendArgument('language-session')
      ->appendMethodCall('getLanguageSwitchLinks')
      ->appendArgument(ClassMethodCallNode::create('\Drupal', 'request'));

    foreach ($call->getArguments() as $argument) {
      $replacement->appendArgument($argument);
    }

    return $replacement;
  }

}
