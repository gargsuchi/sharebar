<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\Watchdog;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class WatchdogTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new Watchdog([], 'Watchdog', []);

    $call = Parser::parseSnippet('watchdog("foo", "Peekaboo, I see you!", array(), WATCHDOG_ERROR);')->firstChild();
    $this->assertEquals('\Drupal::logger("foo")->error("Peekaboo, I see you!", array())', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('watchdog("foo", "Heyo, just saying hi.");')->firstChild();
    $this->assertEquals('\Drupal::logger("foo")->notice("Heyo, just saying hi.", [])', $plugin->rewrite($call)->getText());
  }

}
