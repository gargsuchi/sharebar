<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\Drupal8\ParameterBindingTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Converter\Routing\Drupal8;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\ParameterBinding;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal8\PathUtility;
use Drupal\Tests\UnitTestCase;
use Pharborist\Node;
use Pharborist\Functions\ParameterNode;
use Pharborist\StringNode;

/**
 * @group DMU
 */
class ParameterBindingTest extends UnitTestCase {

  private $path;

  private $parameter;

  private $binding;

  public function __construct() {
    $this->path = new PathUtility('node/{node}/foobar');
    $this->parameter = ParameterNode::create('node');
    $this->binding = new ParameterBinding($this->path, $this->parameter);
  }

  public function testGetParameter() {
    $this->assertSame($this->parameter, $this->binding->getParameter());
  }

  public function testInPath() {
    // We expect $this->binding to be in the path because its parameter is named $node,
    // and {node} is in the path.
    $this->assertTrue($this->binding->inPath());

    $not = new ParameterBinding(new PathUtility('user/{user}/foobar'), $this->parameter);
    $this->assertFalse($not->inPath());
  }

  public function testGetValue() {
    $value = $this->binding->getValue();
    $this->assertInstanceOf('\Drupal\drupalmoduleupgrader\Utility\Path\PathComponentInterface', $value);
    $this->assertEquals($value, '{node}');

    // $blorf is not in the user/{user}/edit path, so we expect either its default value
    // or NULL if it hasn't got one.
    $blorf = ParameterNode::create('blorf');
    $not = new ParameterBinding(new PathUtility('user/{user}/edit'), $blorf);
    $this->assertNull($not->getValue());
    $blorf->setValue(StringNode::fromValue('foobaz'));
    $this->assertEquals('foobaz', $not->getValue());
  }

}
