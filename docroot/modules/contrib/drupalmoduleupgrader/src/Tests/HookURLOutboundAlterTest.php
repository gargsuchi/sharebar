<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the conversion of hook_url_outbound_alter() to an outbound path
 * processor.
 *
 * @group DMU
 */
class HookURLOutboundAlterTest extends KernelTestBase {

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
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('HookURLOutboundAlter');
  }

  public function tearDown() {
    $this->fs->remove('/tmp/foobar');
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'url_outbound_alter');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(1, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $file = $this->target->getPath('src/OutboundPathProcessor.php');
    $this->assertTrue(file_exists($file));

    /** @var \Pharborist\ClassNode $class */
    $class = $this->target
      ->getCodeManager()
      ->open($file)
      ->getNamespace('Drupal\foobar')
      ->getBody()
      ->children(Filter::isClass('OutboundPathProcessor'))
      ->get(0);
    $this->assertTrue($class instanceof ClassNode);
    $this->assertTrue($class->hasMethod('processOutbound'));

    $services_file = $this->target->getPath('.services.yml');
    $this->assertTrue(file_exists($services_file));
    $services = Yaml::parse($services_file);
    $expected = [
      'class' => 'Drupal\foobar\OutboundPathProcessor',
      'tags' => [
        ['name' => 'path_processor_outbound'],
      ],
    ];
    $this->assertIdentical($expected, $services['services']['outbound_path_processor']);
  }

}
