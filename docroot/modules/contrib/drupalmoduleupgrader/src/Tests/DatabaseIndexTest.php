<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Tests\DatabaseIndexTest.
 */

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Functions\FunctionDeclarationNode;
use Pharborist\NodeCollection;
use Pharborist\Parser;

/**
 * Tests the database code indexer.
 *
 * @group DMU
 */
class DatabaseIndexTest extends KernelTestBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $db;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Indexer\IndexerInterface
   */
  private $index;

  public static $modules = ['drupalmoduleupgrader'];

  public function setUp() {
    parent::setUp();
    $this->installSchema('drupalmoduleupgrader', 'dmu_index');

    $this->db = \Drupal::database();
    $this->target = new Target(drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/pants', \Drupal::getContainer());
    $this->index = $this->target->getIndexer();
  }

  public function testCount() {
    $this->assertEqual(3, $this->index->count('class'));
    $this->assertEqual(0, $this->index->count('class', 'Foobaz'));
  }

  public function testHas() {
    $file = $this->target->getBasePath() . '/pants.module';

    $this->assertTrue($this->index->has('function', 'pants_change'));
    $this->assertFalse($this->index->has('function', 'pants_crap'));

    $this->index->add('class', 'Flibbertigibbet', $file);
    $this->assertTrue($this->index->has('class', 'Flibbertigibbet'));
    $this->assertFalse($this->index->has('class', 'Foobaz'));

    $this->index->add('function_call', 'wambooli', $file);
    $this->assertTrue($this->index->has('function_call', 'wambooli'));
    $this->assertFalse($this->index->has('function_call', 'wangdoodle'));

    $this->index->add('web_test', 'FooTest', $file);
    $this->assertTrue($this->index->has('web_test', 'FooTest'));
    $this->assertFalse($this->index->has('web_test', 'BarTest'));

    $this->index->add('unit_test', 'BlorfTest', $file);
    $this->assertTrue($this->index->has('unit_test', 'BlorfTest'));
    $this->assertFalse($this->index->has('unit_test', 'FragusTest'));

    $this->assertTrue($this->index->has('hook', 'menu'));
    $this->assertFalse($this->index->has('hook', 'url_outbound_alter'));
  }

  public function testClear() {
    $this->index->build();
    $this->assertIndexNotEmpty();
    $this->index->clear();
    $this->assertIndexEmpty();
  }

  public function testGet() {
    $this->index->build();

    $classes = $this->index->get('class');
    $this->assertTrue($classes instanceof NodeCollection);
    $this->assertEqual(3, $classes->count());
    $this->assertTrue($classes->get(0) instanceof ClassNode);
    $this->assertTrue($classes->get(1) instanceof ClassNode);
    $this->assertTrue($classes->get(2) instanceof ClassNode);

    $class = $this->index->get('web_test', 'PantsUITestCase')->get(0);
    $this->assertTrue($class instanceof ClassNode);
    $this->assertEqual($class->getName()->getText(), 'PantsUITestCase');

    $user_access = $this->index->get('function_call', 'user_access');
    $this->assertTrue($user_access instanceof NodeCollection);
    $this->assertEqual(2, $user_access->count());
    $this->assertTrue($user_access->get(0) instanceof FunctionCallNode);
    $this->assertTrue($user_access->get(1) instanceof FunctionCallNode);
    $this->assertEqual('user_access', $user_access->get(0)->getName()->getText());
    $this->assertEqual('user_access', $user_access->get(1)->getName()->getText());

    $hook_menu = $this->index->get('hook', 'menu')->get(0);
    $this->assertTrue($hook_menu instanceof FunctionDeclarationNode);
    $this->assertEqual('pants_menu', $hook_menu->getName()->getText());
  }

  public function testDelete() {
    $this->assertIndexContains(65, 'function_call');
    $this->index->delete('function_call', 'user_access');
    $this->assertIndexContains(63, 'function_call');

    $this->assertIndexContains(3, 'class');
    $this->index->delete('class');
    $this->assertIndexContains(0, 'class');

    $this->assertIndexContains(4, 'hook');
    $this->index->delete('hook', 'menu');
    $this->assertIndexContains(3, 'hook');
    $this->index->delete('hook');
    $this->assertIndexContains(0, 'hook');
  }

  private function assertIndexNotEmpty() {
    $index_size = $this->db
      ->select('dmu_index')
      ->condition('module', $this->target->id())
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertNotEqual(0, $index_size);
  }

  private function assertIndexEmpty() {
    $index_size = $this->db
      ->select('dmu_index')
      ->condition('module', $this->target->id())
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertEqual(0, $index_size);
  }

  private function assertIndexContains($expected_count, $object_type) {
    $actual_count = $this->db
      ->select('dmu_index')
      ->condition('module', $this->target->id())
      ->condition('type', $object_type)
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertEqual($expected_count, $actual_count);
  }

}
