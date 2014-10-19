<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\Path\Drupal7;

use Drupal\Tests\UnitTestCase;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal7\PathComponent;

/**
 * @group DMU
 */
class PathComponentTest extends UnitTestCase {

  public function testPathComponent() {
    $placeholder = new PathComponent('%');
    $this->assertTrue($placeholder->isPlaceholder());
    $this->assertFalse($placeholder->isWildcard());
    $this->assertEquals('%', $placeholder->__toString());

    $wildcard = new PathComponent('%node');
    $this->assertTrue($wildcard->isWildcard());
    $this->assertFalse($wildcard->isPlaceholder());
    $this->assertEquals('%node', $wildcard->__toString('%node'));
  }

}
