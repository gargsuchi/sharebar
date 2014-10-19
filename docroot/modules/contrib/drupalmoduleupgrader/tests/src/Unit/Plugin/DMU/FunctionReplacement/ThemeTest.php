<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Theme;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class ThemeTest extends UnitTestCase {

  public function testAlter() {
    $plugin = new Theme([], 'Theme', []);
    $call = Parser::parseSnippet('theme("foo", []);')->firstChild();
    $plugin->alter($call);
    $this->assertEquals('_theme("foo", [])', $call->getText());
  }

}
