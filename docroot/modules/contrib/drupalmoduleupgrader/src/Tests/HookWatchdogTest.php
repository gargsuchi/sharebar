<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the conversion of hook_watchdog() to a PSR-3 compatible logger.
 *
 * @group DMU
 */
class HookWatchdogTest extends KernelTestBase {

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
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('HookWatchdog');
  }

  public function tearDown() {
    $this->fs->remove('/tmp/foobar');
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'watchdog');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(1, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $file = $this->target->getPath('src/Logger/DefaultLogger.php');
    $this->assertTrue(file_exists($file));

    /** @var \Pharborist\TopNode $doc */
    $doc = $this->target->getCodeManager()->open($file);
    $this->assertTrue($doc->hasNamespace('Drupal\foobar\Logger'));
    /** @var \Pharborist\ClassNode $class */
    $class = $doc->getNamespace('Drupal\foobar\Logger')->getBody()->children(Filter::isClass('DefaultLogger'))->get(0);
    $this->assertTrue($class instanceof ClassNode);

    $services_file = $this->target->getPath('.services.yml');
    $this->assertTrue(file_exists($services_file));
    $services = Yaml::parse($services_file);
    $expected = [
      'class' => 'Drupal\foobar\Logger\DefaultLogger',
      'tags' => [
        ['name' => 'logger'],
      ],
    ];
    $this->assertIdentical($expected, $services['services']['default_logger']);
  }

}
