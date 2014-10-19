<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\InfoToYAML;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * @group DMU
 */
class InfoToYAMLTest extends UnitTestCase {

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\InfoToYAML
   */
  private $plugin;

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  private $fs;

  public function setUp() {
    $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
    $this->target = new Target('tests/foobar', $container);
    $this->plugin = new InfoToYAML([], 'InfoToYAML', []);
    $this->fs = new Filesystem();
  }

  public function tearDown() {
    $this->fs->remove($this->target->getPath('.info.yml'));
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
    $this->fs->touch($this->target->getPath('.info.yml'));
    $this->assertFalse($this->plugin->isExecutable($this->target));
  }

  public function testAnalyze() {
    $this->plugin->analyze($this->target);

    $issues = $this->target->getReport()->getIssues();
    $this->assertCount(5, $issues);
  }

  /**
   * @depends testIsExecutable
   */
  public function testConvert() {
    $this->plugin->convert($this->target);

    $info_file = $this->target->getPath('.info.yml');
    $this->assertFileExists($info_file);

    $info = Yaml::parse($info_file);
    $this->assertEquals('8.x', $info['core']);
    $this->assertEquals('module', $info['type']);
    $this->assertNotContains('ctools', $info['dependencies']);
    $this->assertContains('cer', $info['dependencies']);
    $this->assertArrayNotHasKey('files', $info);
    $this->assertArrayNotHasKey('configure', $info);
  }

}
