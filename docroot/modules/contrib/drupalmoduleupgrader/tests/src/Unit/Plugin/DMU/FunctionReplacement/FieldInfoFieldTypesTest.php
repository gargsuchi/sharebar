<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\FieldInfoFieldTypes;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class FieldInfoFieldTypesTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new FieldInfoFieldTypes([], 'FieldInfoFieldTypes', []);

    $call = Parser::parseSnippet('field_info_field_types();')->firstChild();
    $this->assertEquals('\Drupal::service(\'plugin.manager.field.field_type\')->getDefinitions()', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('field_info_field_types("foobaz");')->firstChild();
    $this->assertEquals('\Drupal::service(\'plugin.manager.field.field_type\')->getDefinition("foobaz")', $plugin->rewrite($call)->getText());
  }

}
