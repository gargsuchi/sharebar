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
 * Tests converting a hook_menu() item that calls drupal_get_form into a
 * class implemeting FormInterface.
 *
 * @group DMU
 */
class FormRouteTest extends KernelTestBase {

  public static $modules = ['drupalmoduleupgrader'];

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
      'title' => 'Pants',
      'description' => 'Administer pants.',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('pants_settings'),
      'access callback' => 'user_access',
      'access arguments' => array('administer pants'),
      'type' => 'MENU_NORMAL_ITEM',
      'file' => 'pants.admin.inc',
    ];
    $this->wrapper = new D7Route('admin/config/people/pants', $route);

    $source_path = drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/pants';
    $this->fs = new Filesystem();
    $this->target = new Target($source_path, \Drupal::getContainer());
    $this->plugin = \Drupal::service('plugin.manager.drupalmoduleupgrader.route')->createInstance('drupal_get_form', [ 'route' => $this->wrapper ]);
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('src'));
  }

  public function testConvert() {
    $this->plugin->init($this->target);
    $this->plugin->convert($this->target, $this->wrapper);

    $form_file = $this->target->getPath('src/Form/PantsSettings.php');
    $this->assertTrue(file_exists($form_file));

    /** @var \Pharborist\ClassNode $class */
    $class = $this->target->getCodeManager()->open($form_file)->find(Filter::isClass('PantsSettings'))->get(0);
    $this->assertTrue($class instanceof ClassNode);

    /** @var \Symfony\Component\Routing\Route $definition */
    $definition = $this->plugin->buildRouteDefinition($this->target, $this->wrapper);
    $this->assertTrue($definition instanceof Route);
    $this->assertEqual('/admin/config/people/pants', (string) $definition->getPath());
    $this->assertTrue($definition->hasDefault('_form'));
    $this->assertEqual($class->getName()->getAbsolutePath(), $definition->getDefault('_form'));
    $this->assertTrue($definition->hasRequirement('_permission'));
    $this->assertEqual('administer pants', $definition->getRequirement('_permission'));

    /** @var \Symfony\Component\Routing\Route $route */
    $route = $this->plugin->buildRoute($this->target, $this->wrapper);
    $this->assertTrue($route instanceof D8Route);
  }

}
