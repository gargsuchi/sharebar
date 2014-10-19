<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\Drupal8\ParameterMapTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\Drupal8;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\ParameterMap;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal8\PathUtility;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;
use Symfony\Component\Routing\Route;

/**
 * @group DMU
 */
class ParameterMapTest extends UnitTestCase {

  public function testMap() {
    $path = new PathUtility('foo/{bar}/{baz}');

    /** @var \Pharborist\Functions\FunctionDeclarationNode $fooFunc */
    $fooFunc = Parser::parseSnippet('function foo($bar, $baz) {}');
    $fooMap = new ParameterMap($path, $fooFunc->getParameters());

    /** @var \Pharborist\Functions\FunctionDeclarationNode $barFunc */
    $barFunc = Parser::parseSnippet('function bar($foo, \Drupal\node\NodeInterface $baz) {}');
    $barMap = new ParameterMap($path, $barFunc->getParameters());

    $fooMap->merge($barMap);

    // The path does not contain {foo}, but $foo is a required parameter to
    // bar(). The expectation here is that $route will get a NULL default for
    // $foo.
    $route = new Route('/foo/{bar}/{baz}');
    $fooMap->applyRoute($route);
    $fooMap->finalize();
    $this->assertTrue($route->hasDefault('foo'));
    $this->assertNull($route->getDefault('foo'));
    // Since $bar and $baz were both in the path, they should NOT have defaults.
    $this->assertFalse($route->hasDefault('bar'));
    $this->assertFalse($route->hasDefault('baz'));

    // Parameters with the same name must be snapped into conformance with
    // each other.
    $this->assertEquals('\Drupal\node\NodeInterface', $fooFunc->getParameterByName('baz')->getTypeHint()->getText());
  }

}
