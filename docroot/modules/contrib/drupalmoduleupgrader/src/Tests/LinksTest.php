<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

// Prevents notices (or, as SimpleTest likes to call them, "exceptions") from
// polluting the test results.
define('MENU_CALLBACK', 'MENU_CALLBACK');
define('MENU_NORMAL_ITEM', 'MENU_NORMAL_ITEM');

/**
 * Tests converting hook_menu's linking stuff to plugins defined in YAML.
 *
 * @group DMU
 */
class LinksTest extends KernelTestBase {

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

  public static $modules = ['system', 'user', 'drupalmoduleupgrader'];

  public function setUp() {
    parent::setUp();
    $this->installSchema('system', 'router');
    $this->installSchema('drupalmoduleupgrader', 'dmu_index');

    $source_path = drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/pants';
    $this->fs = new Filesystem();

    $this->target = new Target($source_path, \Drupal::getContainer());
    $this->index = $this->target->getIndexer();
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('Links');
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('.links.menu.yml'));
    $this->fs->remove($this->target->getPath('.links.task.yml'));
    $this->fs->remove($this->target->getPath('.links.action.yml'));
    $this->fs->remove($this->target->getPath('.links.contextual.yml'));
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'menu');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(0, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $links_file = $this->target->getPath('.links.menu.yml');
    $this->assertTrue(file_exists($links_file));
    $expected = [
      'pants.settings' => [
        'route_name' => 'pants.settings',
        'title' => 'Pants',
        'description' => 'Administer pants.',
      ],
    ];
    $this->assertIdentical($expected, Yaml::parse($links_file));
  }

}
