<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\Entity\TermPropertyAccessRewriterTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\Entity;

use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\TermPropertyAccessRewriter;
use Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\EntityPropertyAccessRewriterTest;
use Drupal\Tests\UnitTestCase;
use Pharborist\Functions\ParameterNode;
use Pharborist\Parser;

/**
 * @group DMU
 */
class TermPropertyAccessRewriterTest extends EntityPropertyAccessRewriterTest {

  public function testRewriter() {
    $original = <<<'END'
function foo($term) {
}
END;

    $expected = <<<'END'
function foo(Drupal\taxonomy\TermInterface $term) {
}
END;

    /** @var \Pharborist\Functions\FunctionDeclarationNode $function */
    $function = Parser::parseSnippet($original);
    (new TermPropertyAccessRewriter($function->getParameters()[0], $this->getMockEntityManager('taxonomy_term')))->execute();
    $this->assertEquals($expected, $function->getText());
  }

}
