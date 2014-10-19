<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\ParametricRewriterBaseTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter;

use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\ParametricRewriterBase;
use Drupal\Tests\UnitTestCase;
use Pharborist\Functions\ParameterNode;
use Pharborist\Parser;

/**
 * @group DMU
 */
class ParametricRewriterBaseTest extends UnitTestCase {

  private $parameter, $rewriter;

  public function __construct() {
    $this->parameter = Parser::parseSnippet('function foo($bar) {}')->getParameters()[0];
    $this->rewriter = new ParametricRewriterBase($this->parameter);
  }

  public function testGetParameter() {
    $this->assertSame($this->parameter, $this->rewriter->getParameter());
  }

  /**
   * @depends testGetParameter
   */
  public function testMatch() {
    $master = ParameterNode::create('wooble')->setTypeHint('NodeInterface');
    $this->rewriter->match($master);

    $parameter = $this->rewriter->getParameter();
    $this->assertEquals('NodeInterface', $parameter->getTypeHint());
    $this->assertEquals('wooble', $parameter->getName());
  }

}
