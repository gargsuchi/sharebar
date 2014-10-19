<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

// Prevent 'undefined constant' errors
define('DRUPAL_NO_CACHE', 'DRUPAL_NO_CACHE');

/**
 * Tests the conversion of blocks defined in hook_block_info() to annotated
 * plugins.
 *
 * @group DMU
 */
class HookBlockInfoTest extends KernelTestBase {

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
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('HookBlockInfo');
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('src'));
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'block_info');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(1, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $file = $this->target->getPath('src/Plugin/Block/ChangePants.php');
    $this->assertTrue(file_exists($file));

    /** @var \Pharborist\TopNode $doc */
    $doc = $this->target->getCodeManager()->open($file);
    $this->assertTrue($doc->hasNamespace('Drupal\pants\Plugin\Block'));
    /** @var \Pharborist\ClassNode $class */
    $class = $doc
      ->getNamespace('Drupal\pants\Plugin\Block')
      ->getBody()
      ->children(Filter::isClass('ChangePants'))
      ->get(0);
    $this->assertTrue($class instanceof ClassNode);
    $this->assertTrue($class->hasMethod('build'));

    // @todo Test configurable blocks as well!
  }

}
