<?php

namespace Drupal\drupalmoduleupgrader\Tests;

use Drupal\drupalmoduleupgrader\Target;
use Drupal\drupalmoduleupgrader\Utility\FormConverter;
use Drupal\simpletest\KernelTestBase;
use Pharborist\ClassNode;
use Pharborist\TopNode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests converting a Drupal 7 procedural form to a class implementing
 * FormInterface.
 *
 * @group DMU
 */
class FormConverterTest extends KernelTestBase {

  public static $modules = ['drupalmoduleupgrader'];

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper
   */
  protected $wrapper;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  protected $target;

  public function setUp() {
    parent::setUp();
    $this->installSchema('drupalmoduleupgrader', 'dmu_index');

    $source_path = drupal_get_path('module', 'drupalmoduleupgrader') . '/tests/pants';
    $this->target = new Target($source_path, \Drupal::getContainer());
  }

  public function testConvert() {
    /** @var \Pharborist\Functions\FunctionDeclarationNode $builder */
    $builder = $this->target->getIndexer()->get('function', 'pants_settings')->get(0);
    $converter = new FormConverter($this->target, $builder);
    /** @var \Pharborist\ClassNode $form */
    $form = $converter->generate();

    $this->assertTrue($form instanceof ClassNode);
    $this->assertEqual('PantsSettings', (string) $form->getName());
    $this->assertEqual('ConfigFormBase', (string) $form->getExtends());
    $this->assertTrue($form->hasMethod('buildForm'));
  }

}
