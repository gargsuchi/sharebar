<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\Path\Drupal8;

use Drupal\Tests\UnitTestCase;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal8\PathComponent;

/**
 * @group DMU
 */
class PathComponentTest extends UnitTestCase {

  public function testPathComponent() {
    $wildcard = new PathComponent('{node}');
    $this->assertTrue($wildcard->isWildcard());
    $this->assertEquals('{node}', $wildcard->__toString());
  }

}
