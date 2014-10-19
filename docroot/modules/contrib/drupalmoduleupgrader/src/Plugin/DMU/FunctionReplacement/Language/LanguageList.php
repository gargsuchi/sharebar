<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language\LanguageList.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Language;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "language_default",
 *  description = @Translation("Rewrites calls to language_list()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2174591",
 *      "title" = @Translation("<code>language_list()</code> moved into <var>language_manager</var> service.")
 *    }
 *  },
 *  message = @Translation("<code>language_list()</code> is now a method of the <var>language_handler</var> service")
 * )
 */
class LanguageList extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    return ClassMethodCallNode::create('\Drupal', 'languageManager')
      ->appendMethodCall('getLanguages');
  }

}
