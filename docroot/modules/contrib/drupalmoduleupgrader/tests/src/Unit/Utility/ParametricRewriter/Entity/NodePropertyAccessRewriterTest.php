<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\Entity\NodePropertyAccessRewriterTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\Entity;

use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\NodePropertyAccessRewriter;
use Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\EntityPropertyAccessRewriterTest;
use Drupal\Tests\UnitTestCase;
use Pharborist\Functions\ParameterNode;
use Pharborist\Parser;

/**
 * @group DMU
 */
class NodePropertyAccessRewriterTest extends EntityPropertyAccessRewriterTest {

  public function testRewriter() {
    $original = <<<'END'
function foo($node) {
  echo $node->sticky;
  $node->sticky = TRUE;

  echo $node->status;
  $node->status = TRUE;

  echo $node->promoted;
  $node->promoted = TRUE;

  echo $node->title;
  $node->title = 'Foobaz';

  echo $node->uid;
  $node->uid = 10;

  echo $node->created;
  $node->created = 1;

  echo $node->is_new;
  echo $node->type;
}
END;

    $expected = <<<'END'
function foo(Drupal\node\NodeInterface $node) {
  echo $node->isSticky();
  $node->setSticky(TRUE);

  echo $node->isPublished();
  $node->setPublished(TRUE);

  echo $node->isPromoted();
  $node->setPromoted(TRUE);

  echo $node->getTitle();
  $node->setTitle('Foobaz');

  echo $node->getOwnerId();
  $node->setOwnerId(10);

  echo $node->getCreatedTime();
  $node->setCreatedTime(1);

  echo $node->isNew();
  echo $node->getType();
}
END;

    /** @var \Pharborist\Functions\FunctionDeclarationNode $function */
    $function = Parser::parseSnippet($original);
    (new NodePropertyAccessRewriter($function->getParameters()[0], $this->getMockEntityManager('node')))->execute();
    $this->assertEquals($expected, $function->getText());
  }

}
