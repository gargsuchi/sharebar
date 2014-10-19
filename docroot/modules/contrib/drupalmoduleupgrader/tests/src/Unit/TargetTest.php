<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit;

use Drupal\drupalmoduleupgrader\Issue;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;

/**
 * @group DMU
 */
class TargetTest extends UnitTestCase {

  /**
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  private $container;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  public function setUp() {
    $this->container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');

    $this->target = new Target('tests/pants', $this->container);

    $indexer = $this->getMock('\Drupal\drupalmoduleupgrader\Indexer\IndexerInterface');
    $indexer_factory = $this->getMock('\Drupal\drupalmoduleupgrader\Indexer\IndexerFactoryInterface');
    $indexer_factory
      ->expects($this->any())
      ->method('index')
      ->with($this->target)
      ->will($this->returnValue($indexer));

    $this->container
      ->expects($this->any())
      ->method('get')
      ->with('module_indexer')
      ->will($this->returnValue($indexer_factory));
  }

  /**
   * @expectedException \RuntimeException
   */
  public function testInvalidBasePath() {
    new Target('foobar', $this->container);
  }

  public function testID() {
    $this->assertEquals('pants', $this->target->id());
  }

  public function testGetBasePath() {
    $this->assertEquals('tests/pants', $this->target->getBasePath());
  }

  public function testGetPath() {
    $this->assertEquals('tests/pants/pants.module', $this->target->getPath('.module'));
    $this->assertEquals('tests/pants/pants.install', $this->target->getPath('.install'));
    $this->assertEquals('tests/pants/src/Foobar.php', $this->target->getPath('src/Foobar.php'));
  }

  public function testGetFinder() {
    $this->assertInstanceOf('\Symfony\Component\Finder\Finder', $this->target->getFinder());
  }

  /**
   * @depends testGetFinder
   */
  public function testFinder() {
    $expected = $this->target->getFinder()
      ->exclude('menu_example')
      ->name('*.module')
      ->name('*.install')
      ->name('*.inc')
      ->name('*.test')
      ->name('*.php');
    $this->assertEquals(array_keys(iterator_to_array($expected)), array_keys(iterator_to_array($this->target->getFinder())));
  }

  public function testGetCodeManager() {
    $this->assertInstanceOf('\Drupal\drupalmoduleupgrader\CodeManagerInterface', $this->target->getCodeManager());
  }

  public function testGetIndexer() {
    $this->assertInstanceOf('\Drupal\drupalmoduleupgrader\Indexer\IndexerInterface', $this->target->getIndexer());
  }

  public function testGetReport() {
    $this->assertInstanceOf('\Drupal\drupalmoduleupgrader\ReportInterface', $this->target->getReport());
  }

  public function testGetServices() {
    $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $this->target->getServices());
  }

}
