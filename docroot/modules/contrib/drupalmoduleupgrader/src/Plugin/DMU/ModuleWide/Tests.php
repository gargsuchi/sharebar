<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Tests.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\Converter\IndexerInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Drupal\drupalmoduleupgrader\Utility\Filter\ContainsLogicFilter;
use Pharborist\ClassMemberNode;
use Pharborist\ClassMethodCallNode;
use Pharborist\ClassNode;
use Pharborist\DocCommentNode;
use Pharborist\Filter;
use Pharborist\Parser;
use Pharborist\StringNode;
use Pharborist\TopNode;
use Pharborist\WhitespaceNode;

/**
 * @Converter(
 *  id = "Tests",
 *  description = @Translation("Modifies test classes."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1543796",
 *      "title" = @Translation("Namespacing of automated tests has changed")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2301125",
 *      "title" = @Translation("<code>getInfo()</code> in test classes replaced by doc comments")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/1710766",
 *      "title" = @Translation("Test classes should define a <code>$modules</code> property declaring dependencies")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/1911318",
 *      "title" = @Translation("SimpleTest tests now use empty &quot;testing&quot; profile by default")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/1829160",
 *      "title" = @Translation("New <code>KernelTestBase</code> class for API-level integration tests")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2012184",
 *      "title" = @Translation("PHPUnit added to Drupal core")
 *    }
 *  },
 *  message = @Translation("Automated web tests must be in a PSR-4 namespace, and unit tests must be converted to PHPUnit.")
 * )
 */
class Tests extends ConverterBase implements IndexerInterface {

  private $target;

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    $indexer = $target->getIndexer();
    return ($indexer->has('web_test') || $indexer->has('unit_test'));
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $this->target = $target;

    $target->getIndexer()->get('web_test')->each([ $this, 'convertWeb' ]);
    $target->getIndexer()->get('unit_test')->each([ $this, 'convertUnit' ]);

    $target
      ->getIndexer()
      ->get('class')
      ->filter(function(ClassNode $class) {
        return $class->getExtends() == 'AJAXTestCase';
      })
      ->each([ $this, 'convertAjax' ]);
  }

  /**
   * {@inheritdoc}
   */
  public function index(TargetInterface $target) {
    $indexer = $target->getIndexer();

    foreach ($target->getFinder() as $file) {
      /** @var \SplFileInfo $file */
      if ($file->getExtension() != 'test') {
        continue;
      }

      /** @var \Pharborist\NodeCollection $classes */
      $classes = $target
        ->getCodeManager()
        ->open($file->getPathname())
        ->children(Filter::isInstanceOf('\Pharborist\ClassNode'));

      $classes
        ->filter(function(ClassNode $class) {
          return $class->getExtends() == 'DrupalWebTestCase';
        })
        ->each(function(ClassNode $test) use ($file, $indexer) {
          $indexer->add('web_test', $test->getName()->getText(), $file->getPathname());
        });

      $classes
        ->filter(function(ClassNode $class) {
          return $class->getExtends() == 'DrupalUnitTestCase';
        })
        ->each(function(ClassNode $test) use ($file, $indexer) {
          $indexer->add('unit_test', $test->getName()->getText(), $file->getPathname());
        });

      $classes
        ->filter(function(ClassNode $class) {
          return $class->getExtends() == 'AJAXTestCase';
        })
        ->each(function(ClassNode $test) use ($file, $indexer) {
          $indexer->add('ajax_test', $test->getName()->getText(), $file->getPathname());
        });
    }
  }

  /**
   * Converts a single web test.
   *
   * @param \Pharborist\ClassNode $test
   */
  public function convertWeb(ClassNode $test) {
    $test->setExtends('\Drupal\simpletest\WebTestBase');
    $this->convertInfo($test);
    $this->setModules($test);
    $this->setProfile($test);
    $this->move($test);
  }

  /**
   * Converts the test's getInfo() method to an annotation.
   *
   * @param \Pharborist\ClassNode $test
   */
  private function convertInfo(ClassNode $test) {
    $info = $this->extractInfo($test);

    if ($info) {
      $comment = '';
      $comment .= $info['description'] . "\n\n";
      $comment .= '@group ' . $this->target->id();

      if (isset($info['dependencies'])) {
        $comment .= "\n";
        foreach ($info['dependencies'] as $module) {
          $comment .= '@requires module . ' . $module . "\n";
        }
      }

      $test->setDocComment(DocCommentNode::create($comment));
    }
    else {
      drush_log('Could not get info for test ' . $test->getName() . '.', 'error');
    }
  }

  /**
   * Extracts the return value of the test's getInfo() method, if there's no
   * logic in the method.
   *
   * @param \Pharborist\ClassNode $test
   *
   * @return array|NULL
   */
  private function extractInfo(ClassNode $test) {
    if ($test->hasMethod('getInfo')) {
      $info = $test->getMethod('getInfo');

      if (! $info->is(new ContainsLogicFilter())) {
        return eval($info->getBody()->getText());
      }
    }
  }

  /**
   * Sets the test's $modules property.
   *
   * @param \Pharborist\ClassNode $test
   */
  private function setModules(ClassNode $test) {
    $modules = $this->extractModules($test);
    if ($modules) {
      // @todo Use ClassNode::createProperty() when #124 lands in Pharborist
      $property = Parser::parseSnippet('class Foo { public static $modules = ["' . implode('", "', $modules) . '"]; }')
        ->getBody()
        ->firstChild()
        ->remove();
      $test->appendProperty($property);
    }
  }

  /**
   * Extracts every module required by a web test by scanning its calls
   * to parent::setUp().
   *
   * @param \Pharborist\ClassNode $test
   *
   * @return string[]
   *  Array of modules set up by this module.
   */
  private function extractModules(ClassNode $test) {
    $modules = [];

    $test
      ->find(Filter::isClassMethodCall('parent', 'setUp'))
      ->filter(function(ClassMethodCallNode $call) {
        return (sizeof($call->getArguments()) > 0);
      })
      ->each(function(ClassMethodCallNode $call) use (&$modules) {
        foreach ($call->getArguments() as $argument) {
          if ($argument instanceof StringNode) {
            $modules[] = $argument->toValue();
          }
        }

        $call->clearArguments();
      });

    return array_unique($modules);
  }

  /**
   * Sets the test's $profile property.
   *
   * @param \Pharborist\ClassNode $test
   */
  private function setProfile(ClassNode $test) {
    if (! $test->hasProperty('profile')) {
      $test->appendProperty(ClassMemberNode::create('profile', StringNode::create("'standard'"), 'protected'));
    }
  }

  public function move(ClassNode $test) {
    $ns = 'Drupal\\' . $this->target->id() . '\\Tests';
    TopNode::create($ns)->getNamespace($ns)->append($test->remove());
    WhitespaceNode::create("\n\n")->insertBefore($test);

    $this->writeClass($this->target, $test);
  }

  /**
   * Converts a single unit test.
   *
   * @param \Pharborist\ClassNode $test
   */
  public function convertUnit(ClassNode $test) {
    $test->setExtends('\Drupal\Tests\UnitTestCase');

    $comment_text = <<<END
@FIXME
Unit tests are now written for the PHPUnit framework. You will need to refactor this
test in order for it to work properly.
END;
    $comment = DocCommentNode::create($comment_text);
    $test->setDocComment($comment);

    $ns = 'Drupal\Tests\\' . $this->target->id() . '\Unit';
    $doc = TopNode::create($ns)->getNamespace($ns)->append($test->remove());
    WhitespaceNode::create("\n\n")->insertBefore($test);

    $this->write($this->target, 'tests/src/Unit/' . $test->getName() . '.php', "<?php\n\n$doc");
  }

  /**
   * Converts a single Ajax test.
   *
   * @param \Pharborist\ClassNode $test
   */
  public function convertAjax(ClassNode $test) {
    $test->setExtends('\Drupal\system\Tests\Ajax\AjaxTestBase');
    $this->setModules($test);
    $this->move($test);
  }

}
