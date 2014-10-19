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
 * Tests converting hook_menu's routing stuff to the Symfony routing system.
 *
 * @group DMU
 */
class RoutingTest extends KernelTestBase {

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
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.module')->createInstance('Routing');
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('.routing.yml'));
    $this->fs->remove($this->target->getPath('src'));
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->index->delete('hook', 'menu');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertEqual(2, sizeof($this->target->getReport()->getIssues()));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $routing = $this->target->getPath('.routing.yml');
    $this->assertTrue(file_exists($routing));

    $expected = [
      'pants.change' => [
        'path' => '/pants/change/{account}',
        'defaults' => [
          '_title' => 'Change pants',
          '_content' => '\Drupal\pants\Controller\DefaultController::pants_change',
        ],
        'requirements' => [
          '_custom_access' => '\Drupal\pants\Controller\DefaultController::pants_change_access',
        ],
      ],
      'pants.settings' => [
        'path' => '/admin/config/people/pants',
        'defaults' => [
          '_title' => 'Pants',
          '_form' => '\Drupal\pants\Form\PantsSettings',
        ],
        'requirements' => [
          '_permission' => 'administer pants',
        ],
      ],
    ];
    $this->assertIdentical($expected, Yaml::parse($routing));

    $controller_file = $this->target->getPath('src/Controller/DefaultController.php');
    $this->assertTrue(file_exists($controller_file));
    /** @var \Pharborist\ClassNode $controller */
    $controller = $this->target
      ->getCodeManager()
      ->open($controller_file)
      ->find(Filter::isClass('DefaultController'))
      ->get(0);
    $this->assertTrue($controller instanceof ClassNode);
    $this->assertTrue($controller->hasMethod('pants_change_access'));
    $this->assertTrue($controller->hasMethod('pants_change'));

    $this->assertTrue(file_exists($this->target->getPath('src/Form/PantsSettings.php')));
  }

}
