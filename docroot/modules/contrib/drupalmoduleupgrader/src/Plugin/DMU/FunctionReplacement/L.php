<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\URL.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Pharborist\ArrayNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;
use Pharborist\Parser;
use Pharborist\StringNode;

/**
 * @Converter(
 *  id = "l",
 *  description = @Translation("Rewrites calls to url()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2346779",
 *      "title" = @Translation("<code>l()</code> replaced by URL generation API")
 *    }
 *  },
 *  message = @Translation("<code>l()</code> has been removed."),
 *  fixme = @Translation("@FIXME Provide a valid URL, generated from a route name, as the second argument to l(). See https://www.drupal.org/node/2346779 for more information."),
 *  require_rewrite = true
 * )
 */
class L extends URL {

  /**
   * {@inheritdoc}
   */
  public function canModify(FunctionCallNode $call) {
    $arguments = $call->getArguments();
    return ($arguments[1] instanceof StringNode && $this->routeExists($arguments[1]->toValue()));
  }

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();

    $url = Parser::parseSnippet('url(' . $arguments[1] . ');')->firstChild();

    return ClassMethodCallNode::create('\Drupal', 'l')
      ->appendArgument($arguments[0])
      ->appendArgument(parent::rewrite($url));
  }

}
