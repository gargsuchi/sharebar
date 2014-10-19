<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Indexer\Memory\Indexer;
use Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\PSR4;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;
use Pharborist\Filter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group DMU
 */
class PSR4Test extends UnitTestCase {

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

    $this->plugin = new PSR4([], 'PSR4', [
      'message' => 'Make your classes PSR-4, or suffer the consequences!',
      'documentation' => [],
    ]);
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->target->getIndexer()->delete('class');
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);
    $this->assertCount(1, $this->target->getReport()->getIssues());
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $this->assertFileExists('tests/foobar/src/FrouFrou.php');

    /** @var \Pharborist\TopNode $doc */
    $doc = $this->target->getCodeManager()->open('tests/foobar/src/FrouFrou.php');
    $this->assertTrue($doc->hasNamespace('Drupal\foobar'));
    /** @var \Pharborist\ClassNode $class */
    $class = $doc->getNamespace('Drupal\foobar')->getBody()->children(Filter::isClass('FrouFrou'))->get(0);
    $this->assertInstanceOf('\Pharborist\ClassNode', $class);

    $fs = new Filesystem();
    $fs->remove('tests/foobar/src');
  }

}
