<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\ModuleInvokeAll;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class ModuleInvokeAllTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new ModuleInvokeAll([], 'ModuleInvokeAll', []);

    $call = Parser::parseSnippet('module_invoke_all("cer_fields");')->firstChild();
    $this->assertEquals('\Drupal::moduleHandler()->invokeAll("cer_fields", [])', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('module_invoke_all("menu_alter", $menu);')->firstChild();
    $this->assertEquals('\Drupal::moduleHandler()->invokeAll("menu_alter", [$menu])', $plugin->rewrite($call)->getText());
  }

}
