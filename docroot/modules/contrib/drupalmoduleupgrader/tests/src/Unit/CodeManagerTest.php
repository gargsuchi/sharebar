<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit;

use Drupal\drupalmoduleupgrader\CodeManager;
use Drupal\Tests\UnitTestCase;
use Pharborist\Filter;
use Pharborist\Parser;
use Pharborist\StringNode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group DMU
 */
class CodeManagerTest extends UnitTestCase {

  private $fs;
  private $manager;

  public function setUp() {
    $this->fs = new Filesystem();
    $this->fs->mirror('tests/pants', 'tests/_pants');
    $this->manager = new CodeManager();
  }

  public function tearDown() {
    $this->fs->remove('tests/pants');
    $this->fs->rename('tests/_pants', 'tests/pants');
  }

  public function testOpen() {
    $doc = $this->manager->open('tests/pants/pants.pages.inc');
    $this->assertInstanceOf('\Pharborist\TopNode', $doc);

    $expected = file_get_contents('tests/pants/pants.pages.inc');
    $this->assertEquals($expected, $doc->getText());

    $reDoc = $this->manager->open('tests/pants/pants.pages.inc');
    $this->assertSame($reDoc, $doc);
  }

  public function testSave() {
    $doc = $this->manager->open('tests/pants/pants.pages.inc');
    $call = $doc->find(Filter::isFunctionCall('user_save'))->get(0)->setName('foobar');
    $this->manager->save($call);
    $this->manager->save($doc);

    $this->assertFileExists('tests/pants/pants.pages.inc');
    $written = file_get_contents('tests/pants/pants.pages.inc');
    $this->assertEquals($written, $doc->getText());
  }

  /**
   * @expectedException \Drupal\drupalmoduleupgrader\CodeManagerIOException
   */
  public function testSaveDetachedNode() {
    $function = Parser::parseFile('tests/pants/pants.admin.inc')->children(Filter::isFunction('pants_settings'))->get(0);
    $this->manager->save($function);
  }

  public function testSaveAll() {
    $docA = $this->manager->open('tests/pants/pants.admin.inc');
    $docB = $this->manager->open('tests/pants/pants.pages.inc');

    $docA->children(Filter::isFunction('pants_settings'))->get(0)->setName('pants_settings_form');
    $docB->children(Filter::isFunction('pants_change'))->get(0)->setName('pants_nadger');

    $this->manager->saveAll();
    $this->assertEquals($docA->getText(), file_get_contents('tests/pants/pants.admin.inc'));
    $this->assertEquals($docB->getText(), file_get_contents('tests/pants/pants.pages.inc'));
  }

  public function testCreate() {
    $doc = $this->manager->create('tests/pants/Foobaz.php');
    $this->assertEquals("<?php\n", $doc->getText());

    $this->manager->save($doc);
    $this->assertFileExists('tests/pants/Foobaz.php');

    $doc = $this->manager->create('tests/pants/Foobar.php', 'Drupal\pants');
    $this->assertEquals("<?php\n\nnamespace Drupal\pants;\n", $doc->getText());

    $this->manager->save($doc);
    $this->assertFileExists('tests/pants/Foobar.php');
  }

  public function testFlush() {
    $doc = $this->manager->open('tests/pants/pants.pages.inc');
    $doc->children(Filter::isFunction('pants_change'))->get(0)->remove();

    // Before flushing, opening the file twice should return the already-open doc.
    $reDoc = $this->manager->open('tests/pants/pants.pages.inc');
    $this->assertSame($reDoc, $doc);
    $this->assertEquals($reDoc->getText(), $doc->getText());

    $this->manager->flush();

    // After flushing, opening the file should refresh it, and it should return
    // a totally new copy of the doc.
    $reDoc = $this->manager->open('tests/pants/pants.pages.inc');
    $this->assertNotSame($reDoc, $doc);
    $this->assertNotEquals($reDoc->getText(), $doc->getText());
    $this->assertEquals($reDoc->getText(), file_get_contents('tests/pants/pants.pages.inc'));
  }

}
