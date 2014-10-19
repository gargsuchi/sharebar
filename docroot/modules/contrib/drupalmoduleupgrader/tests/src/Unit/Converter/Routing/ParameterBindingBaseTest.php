<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\ParameterBindingBaseTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\ParameterBinding;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal8\PathUtility;
use Drupal\Tests\UnitTestCase;
use Pharborist\Node;
use Pharborist\Functions\ParameterNode;
use Pharborist\Parser;

/**
 * @group DMU
 */
class ParameterBindingBaseTest extends UnitTestCase {

  /**
   * Implicitly tests enslave(), setMaster(), and conform() methods of
   * ParameterBindingBase.
   */
  public function testConform() {
    $path = new PathUtility('node/{node}/foobar');

    /** @var \Pharborist\Functions\FunctionDeclarationNode $fooFunc */
    $fooFunc = Parser::parseSnippet('function foo(Drupal\node\NodeInterface $node) {}');
    /** @var \Pharborist\Functions\FunctionDeclarationNode $barFunc */
    $barFunc = Parser::parseSnippet('function bar($baz) {}');

    $fooBinding = new ParameterBinding($path, $fooFunc->getParameterByName('node'));
    $barBinding = new ParameterBinding($path, $barFunc->getParameterByName('baz'));

    $fooBinding->enslave($barBinding);
    $fooBinding->conform();

    $baz = $barBinding->getParameter();
    $this->assertEquals('node', $baz->getName());
    $this->assertEquals('Drupal\node\NodeInterface', $baz->getTypeHint());
  }

}
