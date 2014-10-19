<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\DrupalMapAssoc;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class DrupalMapAssocTest extends UnitTestCase {

  public function testAlter() {
    $plugin = new DrupalMapAssoc([], 'DrupalMapAssoc', []);
    $call = Parser::parseSnippet('drupal_map_assoc(array(0, 1, 2, 3));')->firstChild();
    $plugin->alter($call);
    $this->assertEquals('array_combine(array(0, 1, 2, 3), array(0, 1, 2, 3))', $call->getText());
  }

}
