<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\ModuleInvoke;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class ModuleInvokeTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new ModuleInvoke([], 'ModuleInvoke', []);

    $call = Parser::parseSnippet('module_invoke_all("foo", "menu");')->firstChild();
    $this->assertEquals('\Drupal::moduleHandler()->invoke("foo", "menu")', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('module_invoke_all("foo", "menu_alter", $menu);')->firstChild();
    $this->assertEquals('\Drupal::moduleHandler()->invoke("foo", "menu_alter", [$menu])', $plugin->rewrite($call)->getText());
  }

}
