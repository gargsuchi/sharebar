<?php

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\UserAccess;
use Drupal\Tests\UnitTestCase;
use Pharborist\Parser;

/**
 * @group DMU
 */
class UserAccessTest extends UnitTestCase {

  public function testRewrite() {
    $plugin = new UserAccess([], 'UserAccess', []);

    $call = Parser::parseSnippet('user_access("kick ass and take names");')->firstChild();
    $this->assertEquals('\Drupal::currentUser()->hasPermission("kick ass and take names")', $plugin->rewrite($call)->getText());

    $call = Parser::parseSnippet('user_access("be exceptional", $account);')->firstChild();
    $this->assertEquals('$account->hasPermission("be exceptional")', $plugin->rewrite($call)->getText());
  }

}
