<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper as D7Route;
use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\RouteWrapper as D8Route;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Pharborist\TopNode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Route;

/**
 * Tests converting a hook_menu() item pointing to a custom callback into a
 * method of a generic controller.
 *
 * @group DMU
 */
class GenericRouteTest extends KernelTestBase {

  public static $modules = ['user', 'drupalmoduleupgrader'];

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper
   */
  protected $wrapper;

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fs;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  protected $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\RouteConverterInterface
   */
  protected $plugin;

  public function setUp() {
    parent::setUp();
    $this->installSchema('drupalmoduleupgrader', 'dmu_index');

    $route = [
      'title' => 'Change pants',
      'page callback' => 'pants_change',
      'page arguments' => array(2),
      'access callback' => 'pants_change_access', // Default.
      'delivery callback' => 'ajax_deliver',
      'type' => 'MENU_CALLBACK',
      'file' => 'pants.pages.inc',
    ];
    $this->wrapper = new D7Route('pants/change/%user', $route);

    $source_path = drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/pants';
    $this->fs = new Filesystem();
    $this->target = new Target($source_path, \Drupal::getContainer());
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.route')->createInstance('default', [ 'route' => $this->wrapper ]);
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('src'));
  }

  public function testConvert() {
    $this->plugin->init($this->target);
    $this->plugin->convert($this->target, $this->wrapper);

    /** @var \Pharborist\ClassNode $controller */
    $controller = $this->target
      ->getCodeManager()
      ->open($this->target->getPath('src/Controller/DefaultController.php'))
      ->find(Filter::isClass('DefaultController'))
      ->get(0);
    $this->assertTrue($controller->hasMethod('pants_change_access'));
    $this->assertTrue($controller->hasMethod('pants_change'));

    /** @var \Symfony\Component\Routing\Route $definition */
    $definition = $this->plugin->buildRouteDefinition($this->target, $this->wrapper);
    $this->assertTrue($definition instanceof Route);
    $this->assertEqual('/pants/change/{account}', (string) $definition->getPath());
    $this->assertTrue($definition->hasDefault('_content'));
    $this->assertEqual('\Drupal\pants\Controller\DefaultController::pants_change', $definition->getDefault('_content'));
    $this->assertTrue($definition->hasRequirement('_custom_access'));
    $this->assertEqual('\Drupal\pants\Controller\DefaultController::pants_change_access', $definition->getRequirement('_custom_access'));

    /** @var \Symfony\Component\Routing\Route $route */
    $route = $this->plugin->buildRoute($this->target, $this->wrapper);
    $this->assertTrue($route instanceof D8Route);
  }

}
