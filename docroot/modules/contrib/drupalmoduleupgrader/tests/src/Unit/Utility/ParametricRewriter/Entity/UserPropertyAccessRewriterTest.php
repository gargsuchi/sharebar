<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\Entity\UserPropertyAccessRewriterTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\Entity;

use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\UserPropertyAccessRewriter;
use Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\EntityPropertyAccessRewriterTest;
use Drupal\Tests\UnitTestCase;
use Pharborist\Functions\ParameterNode;
use Pharborist\Parser;

/**
 * @group DMU
 */
class UserPropertyAccessRewriterTest extends EntityPropertyAccessRewriterTest {

  public function testRewriter() {
    $original = <<<'END'
function foo($account) {
  echo $account->name;
  $account->name = 'Chaz McRazzmatazz';

  echo $account->mail;
  $account->mail = 'chaz@local.host';

  echo $account->pass;
  $account->pass = 'insecure-password';

  echo $account->is_new;
}
END;

    $expected = <<<'END'
function foo(Drupal\user\UserInterface $account) {
  echo $account->getUsername();
  $account->setUsername('Chaz McRazzmatazz');

  echo $account->getEmail();
  $account->setEmail('chaz@local.host');

  echo $account->getPassword();
  $account->setPassword('insecure-password');

  echo $account->isNew();
}
END;

    /** @var \Pharborist\Functions\FunctionDeclarationNode $function */
    $function = Parser::parseSnippet($original);
    (new UserPropertyAccessRewriter($function->getParameters()[0], $this->getMockEntityManager('user')))->execute();
    $this->assertEquals($expected, $function->getText());
  }

}
