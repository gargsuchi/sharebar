<?php

namespace Drupal\drupalmoduleupgrader\Indexer\Memory;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\drupalmoduleupgrader\Indexer\IndexerInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Functions\FunctionDeclarationNode;
use Pharborist\Node;
use Pharborist\NodeCollection;
use Pharborist\Parser;

/**
 * Indexes a module's code in a database table.
 */
class Indexer implements IndexerInterface {

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  protected $target;

  /**
   * @var \Drupal\drupalmoduleupgrader\CodeManagerInterface
   */
  protected $codeManager;

  /**
   * @var string[]
   */
  protected $hooks = [];

  /**
   * @var \Pharborist\NodeCollection[]
   */
  protected $index = [];

  public function __construct(TargetInterface $target, PluginManagerInterface $plugin_manager) {
    $this->target = $target;
    $this->codeManager = $target->getCodeManager();
    $this->clear();

    foreach ($plugin_manager->getDefinitions() as $definition) {
      if (isset($definition['hook'])) {
        $this->hooks[] = $definition['hook'];
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    foreach ($this->target->getFinder() as $file) {
      /** @var \SplFileInfo $file */
      $doc = $this->codeManager->open($file->getPathname());

      $functions = $doc->children(Filter::isInstanceOf('\Pharborist\Functions\FunctionDeclarationNode'));
      $this->index['function']->add($functions);

      $classes = $doc->children(Filter::isInstanceOf('\Pharborist\ClassNode'));
      $this->index['class']->add($classes);

      $function_calls = $doc->find(Filter::isInstanceOf('\Pharborist\Functions\FunctionCallNode'));
      $this->index['function_call']->add($function_calls);

      $web_tests = $doc
        ->children(Filter::isInstanceOf('\Pharborist\ClassNode'))
        ->filter(function(ClassNode $class) {
          return in_array($class->getExtends(), [ 'DrupalWebTestCase', 'AJAXTestCase' ]);
        });
      $this->index['web_test']->add($web_tests);

      $unit_tests = $doc
        ->children(Filter::isInstanceOf('\Pharborist\ClassNode'))
        ->filter(function(ClassNode $class) {
          return $class->getExtends() == 'DrupalUnitTestCase';
        });
      $this->index['unit_test']->add($unit_tests);

      foreach ($this->hooks as $hook) {
        $implementations = $doc->children(Filter::isFunction($this->target->id() . '_' . $hook));
        $this->index['hook']->add($implementations);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function clear() {
    $this->index = [
      'function' =>
        new NodeCollection([]),
      'class' =>
        new NodeCollection([]),
      'function_call' =>
        new NodeCollection([]),
      'web_test' =>
        new NodeCollection([]),
      'unit_test' =>
        new NodeCollection([]),
      'hook' =>
        new NodeCollection([]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function has($object_type, $identifier = NULL) {
    return (boolean) $this->count($object_type, $identifier);
  }

  /**
   * {@inheritdoc}
   */
  public function count($object_type, $identifier = NULL) {
    return $this->get($object_type, $identifier)->count();
  }

  /**
   * {@inheritdoc}
   */
  public function get($object_type, $identifier = NULL) {
    if ($identifier) {
      if ($object_type == 'hook') {
        $identifier = $this->target->id() . '_' . $identifier;
      }

      return $this->index[$object_type]->filter(function(Node $node) use ($identifier) {
        return $node->getName()->getText() == $identifier;
      });
    }
    else {
      return $this->index[$object_type];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function add($object_type, $identifier, $file) {
    if (empty($this->index[$object_type])) {
      $this->index[$object_type] = new NodeCollection([]);
    }

    $doc = $this->codeManager->open($file);

    switch ($object_type) {
      case 'class':
      case 'web_test':
      case 'unit_test':
        $objects = $doc->children(Filter::isClass($identifier));
        break;

      case 'function':
      case 'hook':
        if ($object_type == 'hook') {
          $identifier = $this->target->id() . '_' . $identifier;
        }
        $objects = $doc->children(Filter::isFunction($identifier));
        break;

      case 'function_call':
        $objects = $doc->find(Filter::isFunctionCall($identifier));
        break;

      default:
        return;
    }

    $this->index[$object_type]->add($objects);
  }

  /**
   * {@inheritdoc}
   */
  public function delete($object_type, $identifier = NULL) {
    if ($identifier) {
      if ($object_type == 'hook') {
        $identifier = $this->target->id() . '_' . $identifier;
      }

      $this->index[$object_type] = $this->index[$object_type]->not(function(Node $node) use ($identifier) {
        return $node->getName()->getText() == $identifier;
      });
    }
    else {
      $this->index[$object_type] = new NodeCollection([]);
    }
  }

}
