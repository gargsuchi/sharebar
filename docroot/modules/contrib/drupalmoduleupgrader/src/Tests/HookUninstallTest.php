<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the disabling of variable_get() calls in hook_uninstall implementations.
 *
 * @group DMU
 */
class HookUninstallTest extends KernelTestBase {

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

    $source_path = drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/foobar';
    $this->fs = new Filesystem();
    $this->fs->mirror($source_path, '/tmp/foobar');

    $this->target = new Target('/tmp/foobar', \Drupal::getContainer());
    $this->index = $this->target->getIndexer();
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('HookUninstall');
  }

  public function tearDown() {
    $this->fs->remove('/tmp/foobar');
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->get('hook', 'uninstall')->get(0)->find(Filter::isFunctionCall('variable_del'))->remove();
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(1, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $expected = <<<'END'
/**
 * Implements hook_uninstall().
 */
function foobar_uninstall() {
  // You no longer need to delete module variables during uninstall; this is handled automatically.
// variable_del('foobar_garbage');

}
END;
    debug(trim($expected));
    debug($this->target->getIndexer()->get('hook', 'uninstall')->get(0)->getText());
    $this->assertEqual(trim($expected), $this->target->getIndexer()->get('hook', 'uninstall')->get(0)->getText());
  }

}
