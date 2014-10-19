<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language\LanguageURLSplitPrefix.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;
use Pharborist\Parser;

/**
 * @Converter(
 *  id = "language_url_split_prefix",
 *  description = @Translation("Rewrites calls to language_url_split_prefix()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2174591",
 *      "title" = @Translation("<code>language_url_split_prefix()</code> is now a method of the <var>language-url</var> language negotiator")
 *    }
 *  },
 *  message = @Translation("<code>language_url_split_prefix()</code> is now a method of a language negotiation plugin")
 * )
 */
class LanguageURLSplitPrefix extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    return ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromScalar('plugin.manager.language_negotiation_method'))
      ->appendMethodCall('createInstance')
      ->appendArgument('language-url')
      ->appendMethodCall('processInbound')
      ->appendArgument(Parser::parseSnippet('current_path()')->firstChild())
      ->appendArgument(ClassMethodCallNode::create('\Drupal', 'request'));
  }

}
