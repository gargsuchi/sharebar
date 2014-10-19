<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Theme.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallAlter;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "theme",
 *  description = @Translation("Translates theme() calls to _theme()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2195739",
 *      "title" = @Translation("theme() renamed to _theme() and should not be called directly")
 *    }
 *  },
 *  message = @Translation("<code>theme()</code> has been renamed to <code>_theme()</code>, and should never be called directly.")
 * )
 */
class Theme extends FunctionCallAlter {

  /**
   * {@inheritdoc}
   */
  public function alter(FunctionCallNode $call) {
    $call->setName('_theme');
  }

}
