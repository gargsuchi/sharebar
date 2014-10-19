<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Indexer\Memory\Indexer;
use Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Tests;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group DMU
 */
class TestsTest extends UnitTestCase {

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\InfoToYAML
   */
  private $plugin;

  public function setUp() {
    $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
    $plugin_manager = $this->getMock('\Drupal\Component\Plugin\PluginManagerInterface');
    $indexer_factory = $this->getMock('\Drupal\drupalmoduleupgrader\Indexer\IndexerFactoryInterface');

    $this->target = new Target('tests/foobar', $container);

    $plugin_manager
      ->expects($this->any())
      ->method('getDefinitions')
      ->willReturn([]);

    $indexer = new Indexer($this->target, $plugin_manager);
    $indexer->build();

    $indexer_factory
      ->expects($this->any())
      ->method('index')
      ->with($this->target)
      ->willReturn($indexer);

    $container
      ->expects($this->any())
      ->method('get')
      ->with('module_indexer')
      ->willReturn($indexer_factory);

    $this->plugin = new Tests([], 'Tests', [
      'message' => 'You need to convert your tests, brah.',
      'documentation' => [],
    ]);
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->target->getIndexer()->delete('web_test');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertCount(1, $this->target->getReport()->getIssues());
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $this->assertFileExists('tests/foobar/src/Tests/FoobarUITestCase.php');

    /** @var \Pharborist\ClassNode $test */
    $test = $this->target->getCodeManager()->open('tests/foobar/src/Tests/FoobarUITestCase.php')->find(Filter::isClass('FoobarUITestCase'))->get(0);
    $this->assertInstanceOf('\Pharborist\ClassNode', $test);
    $this->assertEquals('\Drupal\simpletest\WebTestBase', $test->getExtends()->getText());

    $comment = $test->getDocComment()->getText();
    $this->assertInternalType('integer', strPos($comment, "Tests the Foobar UI."));
    $this->assertInternalType('integer', strPos($comment, '@group foobar'));

    $this->assertTrue($test->hasProperty('modules'));
    $this->assertTrue($test->hasProperty('profile'));
    // @todo Test properties of $modules and $profile. At the moment,
    // Pharborist seems to be a bit borked (the property's parent is a
    // CommaListNode).

    $fs = new Filesystem();
    $fs->remove('tests/foobar/src');
  }

}
