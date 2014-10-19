<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\ThemeGetRegistry;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class ThemeGetRegistryTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new ThemeGetRegistry([], 'ThemeGetRegistry', []);

    $call = Parser::parseSnippet('theme_get_registry();')->firstChild();
    $this->assertEquals('\Drupal::service(\'theme.registry\')->get()', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('theme_get_registry(FALSE);')->firstChild();
    $this->assertEquals('\Drupal::service(\'theme.registry\')->getRuntime()', $plugin->rewrite($call)->getText());
  }

}
