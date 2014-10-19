<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit;

use Drupal\drupalmoduleupgrader\CodeManager;
use Drupal\drupalmoduleupgrader\Issue;
use Drupal\drupalmoduleupgrader\Target;
use Drupal\Tests\UnitTestCase;
use Pharborist\Filter;
use Pharborist\Parser;
use Pharborist\StringNode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group DMU
 */
class IssueTest extends UnitTestCase {

  /**
   * @var \Drupal\drupalmoduleupgrader\IssueInterface
   */
  private $issue;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  private $target;

  public function setUp() {
    $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
    $this->target = new Target('tests/pants', $container);
    $this->issue = new Issue($this->target);
  }

  public function testTitle() {
    $this->issue->setTitle('Foobar');
    $this->assertEquals('Foobar', $this->issue->getTitle());
  }

  public function testSummary() {
    $this->issue->setSummary('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.');
    $this->assertEquals('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.', $this->issue->getSummary());
  }

  public function testDocumentation() {
    $this->issue->addDocumentation('http://www.google.com', 'Just Google it, baby!');
    $documentation = $this->issue->getDocumentation();
    $this->assertTrue(is_array($documentation));
    $this->assertCount(1, $documentation);
    $this->assertArrayHasKey('url', $documentation[0]);
    $this->assertArrayHasKey('title', $documentation[0]);
    $this->assertEquals('http://www.google.com', $documentation[0]['url']);
    $this->assertEquals('Just Google it, baby!', $documentation[0]['title']);
  }

  public function testViolationsAndDetectors() {
    $converter = $this->getMock('\Drupal\drupalmoduleupgrader\Converter\ConverterInterface');
    $this->issue->addAffectedFile('tests/pants/pants.info', $converter);

    $node = $this->target
      ->getCodeManager()
      ->open('tests/pants/pants.module')
      ->children(Filter::isFunction('pants_permission'))
      ->get(0);
    $this->issue->addViolation($node, $converter);

    $violations = $this->issue->getViolations();
    $this->assertTrue(is_array($violations));
    $this->assertCount(2, $violations);
    $this->assertArrayHasKey('file', $violations[0]);
    $this->assertArrayNotHasKey('line_number', $violations[0]);
    $this->assertEquals('tests/pants/pants.info', $violations[0]['file']);
    $this->assertArrayHasKey('file', $violations[1]);
    $this->assertArrayHasKey('line_number', $violations[1]);
    $this->assertEquals('tests/pants/pants.module', $violations[1]['file']);

    $detectors = $this->issue->getDetectors();
    $this->assertTrue(is_array($detectors));
    $this->assertCount(1, $detectors);
    $this->assertEquals(get_class($converter), $detectors[0]);
  }

  public function testErrorLevel() {
    $this->assertEquals('error', $this->issue->getErrorLevel());
    $this->assertEquals('warning', $this->issue->setErrorLevel('warning')->getErrorLevel());
  }

}
