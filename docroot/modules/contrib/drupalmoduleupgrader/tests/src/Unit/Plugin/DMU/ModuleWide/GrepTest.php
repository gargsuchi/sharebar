<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\ModuleWide;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Grep;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group DMU
 */
class GrepTest extends UnitTestCase {

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Grep
   */
  private $plugin;

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  private $fs;

  public function setUp() {
    $this->fs = new Filesystem();
    $this->fs->mirror('tests/pants', '/tmp/pants');

    $container = new ContainerBuilder();
    $this->target = new Target('/tmp/pants', $container);

    $configBuilder = $this->getMockBuilder('\Drupal\Core\Config\Config');
    $configBuilder->disableOriginalConstructor();
    $config = $configBuilder->getMock();
    $config
      ->expects($this->any())
      ->method('get')
      ->willReturnMap([
        ['globals', ['user' => '\Drupal::currentUser()']],
        ['function_calls', []],
        ['constants', ['LANGUAGE_NONE' => '\Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED']],
      ]);

    $this->plugin = new Grep([], 'Grep', [], $config);
  }

  public function tearDown() {
    $this->fs->remove($this->target->getBasePath());
  }

  public function testIsExecutable() {
    $this->assertTrue($this->plugin->isExecutable($this->target));
  }

  public function testConvert() {
    $this->plugin->convert($this->target);

    $contents = file_get_contents($this->target->getPath('.module'));
    $this->assertFalse(strPos($contents, 'global $user'));
    $this->assertInternalType('integer', strPos($contents, '\Drupal::currentUser()'));
    $this->assertFalse(strPos($contents, 'LANGUAGE_NONE'));
    $this->assertInternalType('integer', strPos($contents, '\Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED'));
  }

}
