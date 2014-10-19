<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Tests\ParameterMapTest.
 */

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\ParameterMap;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal7\PathUtility;
use Drupal\simpletest\KernelTestBase;
use Pharborist\Parser;
use Symfony\Component\Routing\Route;

/**
 * Tests mapping of callback function parameters to Drupal 7 routes.
 *
 * @group DMU
 */
class ParameterMapTest extends KernelTestBase {

  public static $modules = ['node', 'user'];

  public function testMap() {
    $path = new PathUtility('foo/%node/%');

    /** @var \Pharborist\Functions\FunctionDeclarationNode $page */
    $page = Parser::parseSnippet('function make_page($node, $baz) {}');
    $pageMap = new ParameterMap($path, $page->getParameters(), [1, 2]);

    /** @var \Pharborist\Functions\FunctionDeclarationNode $access */
    $access = Parser::parseSnippet('function check_access($ziggy) {}');
    $accessMap = new ParameterMap($path, $access->getParameters(), [1]);

    $map = new ParameterMap($path, []);
    $map->merge($accessMap);
    $map->merge($pageMap);

    $map->applyPath();
    $this->assertEqual('foo/{node}/{baz}', (string) $path);

    // The path does not contain {foo}, but $foo is a required parameter to
    // bar(). The expectation here is that $route will get a NULL default for
    // $foo.
    $route = new Route('');
    $map->applyRoute($route);
    $map->finalize();
    $this->assertEqual('/foo/{node}/{baz}', $route->getPath());
    $this->assertFalse($access->hasParameter('ziggy'));
    $this->assertTrue($access->hasParameter('node'));
    $this->assertEqual($access->getParameter('node')->getTypeHint()->getText(), 'Drupal\node\NodeInterface');
    $this->assertTrue($page->hasParameter('node'));
    $this->assertEqual($page->getParameter('node')->getTypeHint()->getText(), 'Drupal\node\NodeInterface');
    $this->assertTrue($page->hasParameter('baz'));
  }

}
