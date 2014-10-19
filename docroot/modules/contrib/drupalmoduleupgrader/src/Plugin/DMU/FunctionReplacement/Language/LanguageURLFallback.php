<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language\LanguageURLFallback.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "language_url_fallback",
 *  description = @Translation("Rewrites calls to language_url_fallback()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2174591",
 *      "title" = @Translation("<code>language_url_fallback()</code> is now a language negotation plugin")
 *    }
 *  },
 *  message = @Translation("<code>language_url_fallback()</code> is now a language negotiation plugin")
 * )
 */
class LanguageURLFallback extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    return ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(Node::fromScalar('plugin.manager.language_negotiation_method'))
      ->appendMethodCall('createInstance')
      ->appendArgument('language-url-fallback')
      ->appendMethodCall('getLangcode');
  }

}
