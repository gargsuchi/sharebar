<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Indexer\Memory;

use Drupal\drupalmoduleupgrader\Indexer\Memory\Indexer;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the in-memory indexer.
 *
 * @group DMU
 */
class IndexerTest extends UnitTestCase {

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Indexer\IndexerInterface
   */
  private $index;

  public function setUp() {
    $plugin_manager = $this->getMock('\Drupal\Component\Plugin\PluginManagerInterface');
    $plugin_manager
      ->expects($this->any())
      ->method('getDefinitions')
      ->willReturn([
        ['hook' => 'menu'],
        ['hook' => 'permission'],
        ['hook' => 'url_outbound_alter'],
      ]);

    $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
    $this->target = new Target('tests/pants', $container);
    $this->index = new Indexer($this->target, $plugin_manager);
    $this->index->build();
  }

  public function testHas() {
    $this->assertTrue($this->index->has('function'));
    $this->assertFalse($this->index->has('unit_test'));
    $this->assertTrue($this->index->has('hook'));
    $this->assertTrue($this->index->has('hook', 'permission'));
    $this->assertFalse($this->index->has('hook', 'url_outbound_alter'));
  }

  public function testCount() {
    $this->assertEquals(3, $this->index->count('class'));
    $this->assertEquals(65, $this->index->count('function_call'));
    $this->assertEquals(0, $this->index->count('unit_test'));
    $this->assertEquals(2, $this->index->count('function_call', 'variable_get'));
  }

  public function testGet() {
    $classes = $this->index->get('class');
    $this->assertInstanceOf('\Pharborist\NodeCollection', $classes);
    $this->assertCount(3, $classes);
    $this->assertInstanceOf('\Pharborist\ClassNode', $classes->get(0));
    $this->assertInstanceOf('\Pharborist\ClassNode', $classes->get(1));
    $this->assertInstanceOf('\Pharborist\ClassNode', $classes->get(2));

    $hook = $this->index->get('hook', 'permission')->get(0);
    $this->assertInstanceOf('\Pharborist\Functions\FunctionDeclarationNode', $hook);
    $this->assertEquals('pants_permission', $hook->getName()->getText());
  }

  public function testClear() {
    $this->index->clear();
    $this->assertFalse($this->index->has('class'));
  }

  public function testAdd() {
    $this->index->clear();
    $this->index->add('class', 'PantsUITestCase', $this->target->getPath('.test'));
    $this->assertTrue($this->index->has('class', 'PantsUITestCase'));
  }

  public function testDelete() {
    $this->index->delete('function_call', 'user_save');
    $this->assertEquals(64, $this->index->count('function_call'));
    $this->index->delete('class');
    $this->assertFalse($this->index->has('class'));
    $this->index->delete('hook', 'permission');
    $this->assertFalse($this->index->has('hook', 'permission'));
  }

}
