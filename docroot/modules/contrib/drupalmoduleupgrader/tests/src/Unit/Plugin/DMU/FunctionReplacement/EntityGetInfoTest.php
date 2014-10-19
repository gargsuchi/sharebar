<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\EntityGetInfo;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class EntityGetInfoTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new EntityGetInfo([], 'EntityGetInfo', []);

    $call = Parser::parseSnippet('entity_get_info();')->firstChild();
    $this->assertEquals('\Drupal::entityManager()->getDefinitions()', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('entity_get_info("node");')->firstChild();
    $this->assertEquals('\Drupal::entityManager()->getDefinition("node")', $plugin->rewrite($call)->getText());
  }

}
