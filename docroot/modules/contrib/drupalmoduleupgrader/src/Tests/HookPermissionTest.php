<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the conversion of static hook_permission() implementations into
 * MODULE.permissions.yml files.
 *
 * @group DMU
 */
class HookPermissionTest extends KernelTestBase {

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  private $fs;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Indexer\IndexerInterface
   */
  private $index;

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\ConverterInterface
   */
  private $plugin;

  public static $modules = ['drupalmoduleupgrader'];

  public function setUp() {
    parent::setUp();
    $this->installSchema('drupalmoduleupgrader', 'dmu_index');

    $source_path = drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/pants';
    $this->fs = new Filesystem();

    $this->target = new Target($source_path, \Drupal::getContainer());
    $this->index = $this->target->getIndexer();
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('HookPermission');
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('.permissions.yml'));
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'permission');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(1, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $permissions_file = $this->target->getPath('.permissions.yml');
    $this->assertTrue(file_exists($permissions_file));
    $this->assertIdentical(Yaml::parse($permissions_file), pants_permission());
  }

}
