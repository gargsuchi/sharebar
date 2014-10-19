<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\FieldInfoWidgetTypes;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class FieldInfoWidgetTypesTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new FieldInfoWidgetTypes([], 'FieldInfoWidgetTypes', []);

    $call = Parser::parseSnippet('field_info_widget_types();')->firstChild();
    $this->assertEquals('\Drupal::service(\'plugin.manager.field.widget\')->getDefinitions()', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('field_info_widget_types("my_widget");')->firstChild();
    $this->assertEquals('\Drupal::service(\'plugin.manager.field.widget\')->getDefinition("my_widget")', $plugin->rewrite($call)->getText());
  }

}
