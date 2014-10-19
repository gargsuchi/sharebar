<?php

/**
 * @file
 * Contains \Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter\EntityPropertyAccessRewriterTest.
 */

namespace Drupal\Tests\drupalmoduleupgrader\Unit\Utility\ParametricRewriter;

use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\EntityPropertyAccessRewriter;
use Drupal\Tests\UnitTestCase;
use Pharborist\Functions\ParameterNode;
use Pharborist\Parser;

/**
 * @group DMU
 */
class EntityPropertyAccessRewriterTest extends UnitTestCase {

  public function testRewriter() {
    $original = <<<'END'
function foo($node) {
  echo $node->nid;
  echo $node->type;
  echo $node->title;
}
END;

    $expected = <<<'END'
function foo($node) {
  echo $node->id();
  echo $node->bundle();
  echo $node->label();
}
END;

    /** @var \Pharborist\Functions\FunctionDeclarationNode $function */
    $function = Parser::parseSnippet($original);
    (new EntityPropertyAccessRewriter('node', $function->getParameters()[0], $this->getMockEntityManager('node')))->execute();
    $this->assertEquals($expected, $function->getText());
  }

  protected function getMockEntityManager($entity_type) {
    $entity_type_mock = $this->getMock('\Drupal\Core\Entity\EntityTypeInterface');
    $entity_type_mock
      ->expects($this->any())
      ->method('getKey')
      ->will($this->returnValueMap(
        [
          ['id', 'nid'],
          ['bundle', 'type'],
          ['label', 'title'],
        ]
      ));

    $entity_manager = $this->getMock('\Drupal\Core\Entity\EntityManagerInterface');
    $entity_manager
      ->expects($this->any())
      ->method('getDefinition')
      ->with($entity_type)
      ->will($this->returnValue($entity_type_mock));

    return $entity_manager;
  }

}
