<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\Drupal7\ParameterBindingTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\Drupal7;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\ParameterBinding;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal7\PathUtility;
use Drupal\Tests\UnitTestCase;
use Pharborist\Node;
use Pharborist\Functions\ParameterNode;

/**
 * @group DMU
 */
class ParameterBindingTest extends UnitTestCase {

  private $a, $b, $c, $d;

  public function __construct() {
    $parameter = ParameterNode::create('node');

    $this->a = new ParameterBinding(new PathUtility('node/%node/foo'), $parameter, 1);
    $this->b = new ParameterBinding(new PathUtility('node/%node/baz'), $parameter, 'herro');
    $this->c = new ParameterBinding(new PathUtility('node/%node/bar'), $parameter);
    $this->d = new ParameterBinding(new PathUtility('node/%node/ni'), $parameter, 3);
  }

  public function testHasArgument() {
    $this->assertTrue($this->a->hasArgument());
    $this->assertTrue($this->b->hasArgument());
    $this->assertFalse($this->c->hasArgument());
    $this->assertTrue($this->d->hasArgument());
  }

  public function testGetArgument() {
    $this->assertSame(1, $this->a->getArgument());
    $this->assertSame('herro', $this->b->getArgument());
    $this->assertSame(ParameterBinding::NO_ARGUMENT, $this->c->getArgument());
    $this->assertSame(3, $this->d->getArgument());
  }

  public function testInPath() {
    $this->assertTrue($this->a->inPath());
    $this->assertFalse($this->b->inPath());
    $this->assertFalse($this->c->inPath());
    $this->assertFalse($this->d->inPath());
  }

  public function testIsPathPosition() {
    $this->assertTrue($this->a->isPathPosition());
    $this->assertFalse($this->b->isPathPosition());
    $this->assertFalse($this->c->isPathPosition());
    $this->assertTrue($this->d->isPathPosition());
  }

  public function testGetValue() {
    // A is a path component, so it should be returned
    $this->assertInstanceOf('\Drupal\drupalmoduleupgrader\Utility\Path\PathComponentInterface', $this->a->getValue());
    $this->assertTrue($this->a->getValue()->isWildcard());
    $this->assertEquals('%node', $this->a->getValue());

    // There's an explicit value for B
    $this->assertEquals('herro', $this->b->getValue());

    // There's no explicit value for C, so expect the parameter's default.
    $this->assertNull($this->c->getValue());

    /** @var \Drupal\drupalmoduleupgrader\Utility\Path\Drupal7\PathComponent $placeholder */
    $this->assertInstanceOf('\Drupal\drupalmoduleupgrader\Utility\Path\PathComponentInterface', $this->d->getValue());
    $this->assertTrue($this->d->getValue()->isPlaceholder());
    $this->assertEquals('%', $this->d->getValue());
  }

}
