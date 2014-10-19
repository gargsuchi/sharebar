<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\DrupalMapAssoc.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallAlter;
use Pharborist\Functions\FunctionCallNode;

/**
 * @Converter(
 *  id = "drupal_map_assoc",
 *  description = @Translation("Converts calls to drupal_map_assoc()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2207453",
 *      "title" = @Translation("<code>drupal_map_assoc()</code> removed")
 *    }
 *  },
 *  message = @Translation("drupal_map_assoc() has been removed.")
 * )
 */
class DrupalMapAssoc extends FunctionCallAlter {

  /**
   * {@inheritdoc}
   */
  public function alter(FunctionCallNode $call) {
    // Change function name to array_combine().
    $call->setName('array_combine');

    // Duplicate the first $array argument twice (silly, but true).
    // Need to clone the argument to make a copy of it, since Pharborist works
    // on original tree elements.
    $arguments = $call->getArguments();
    $call->appendArgument(clone $arguments[0]);
  }

}
