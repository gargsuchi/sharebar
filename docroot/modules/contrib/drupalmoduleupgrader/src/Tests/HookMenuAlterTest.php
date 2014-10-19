<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the generation of boilerplate code to convert hook_menu_alter() to
 * its equivalent Drupal 8 mechanisms.
 *
 * @group DMU
 */
class HookMenuAlterTest extends KernelTestBase {

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
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('HookMenuAlter');
  }

  public function tearDown() {
    $this->fs->remove('/tmp/foobar');
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'menu_alter');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(1, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $this->assertTrue(file_exists($this->target->getPath('src/Routing/RouteSubscriber.php')));
    $subscriber = $this->target->getCodeManager()->open($this->target->getPath('src/Routing/RouteSubscriber.php'))->find(Filter::isClass('RouteSubscriber'))->get(0);
    $this->assertTrue($subscriber instanceof ClassNode);

    $doc = $this->target->getCodeManager()->open($this->target->getPath('.module'));
    $this->assertEqual(1, $doc->children(Filter::isFunction('foobar_menu_links_discovered_alter'))->count());
    $this->assertEqual(1, $doc->children(Filter::isFunction('foobar_menu_local_tasks_alter'))->count());
    $this->assertEqual(1, $doc->children(Filter::isFunction('foobar_menu_local_actions_alter'))->count());
    $this->assertEqual(1, $doc->children(Filter::isFunction('foobar_contextual_links_view_alter'))->count());
  }

}
