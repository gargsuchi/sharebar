<?php

namespace Drupal\drupalmoduleupgrader\Indexer\Database;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Database\Connection as DatabaseConnection;
use Drupal\drupalmoduleupgrader\Indexer\IndexerInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Functions\FunctionDeclarationNode;
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
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * Hooks with plugin coverage.
   *
   * @var string[]
   */
  protected $hooks = [];

  public function __construct(TargetInterface $target, DatabaseConnection $db, PluginManagerInterface $plugin_manager) {
    $this->target = $target;
    $this->codeManager = $target->getCodeManager();
    $this->db = $db;

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

      $doc
        ->children(Filter::isInstanceOf('\Pharborist\Functions\FunctionDeclarationNode'))
        ->each(function(FunctionDeclarationNode $function) use ($file) {
          $this->add('function', $function->getName()->getText(), $file->getPathname());
        });

      $doc
        ->children(Filter::isInstanceOf('\Pharborist\ClassNode'))
        ->each(function(ClassNode $class) use ($file) {
          $this->add('class', $class->getName()->getText(), $file->getPathname());
        });

      $doc
        ->find(Filter::isInstanceOf('\Pharborist\Functions\FunctionCallNode'))
        ->each(function(FunctionCallNode $call) use ($file) {
          $this->add('function_call', $call->getName()->getText(), $file->getPathname());
        });

      $doc
        ->children(Filter::isInstanceOf('\Pharborist\ClassNode'))
        ->filter(function(ClassNode $class) {
          return $class->getExtends() == 'DrupalWebTestCase';
        })
        ->each(function(ClassNode $test) use ($file) {
          $this->add('web_test', $test->getName()->getText(), $file->getPathname());
        });

      $doc
        ->children(Filter::isInstanceOf('\Pharborist\ClassNode'))
        ->filter(function(ClassNode $class) {
          return $class->getExtends() == 'DrupalUnitTestCase';
        })
        ->each(function(ClassNode $test) use ($file) {
          $this->add('unit_test', $test->getName()->getText(), $file->getPathname());
        });

      foreach (array_unique($this->hooks) as $hook) {
        $functions = $doc->children(Filter::isFunction($this->target->id() . '_' . $hook));
        if ($functions->count() === 1) {
          $this->add('hook', $hook, $file->getPathname());
        }
      }
    }

    $this->codeManager->flush();
  }

  /**
   * {@inheritdoc}
   */
  public function clear() {
    $this->db
      ->delete('dmu_index')
      ->condition('module', $this->target->id())
      ->execute();
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
    $query = $this->db
      ->select('dmu_index', 'i')
      ->condition('module', $this->target->id())
      ->condition('type', $object_type);

    if ($identifier) {
      $query->condition('name', $identifier);
    }

    return $query->countQuery()->execute()->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function get($object_type, $identifier = NULL) {
    $objects = $this->db
      ->select('dmu_index', 'i')
      ->fields('i')
      ->condition('module', $this->target->id())
      ->condition('type', $object_type);

    if ($identifier) {
      $objects->condition('name', $identifier);
    }

    $collection = new NodeCollection([]);
    foreach ($objects->execute() as $object) {
      $doc = $this->codeManager->open($object->file);

      switch ($object_type) {
        case 'class':
        case 'web_test':
        case 'unit_test':
          $collection->add($doc->find(Filter::isClass($object->name)));
          break;

        case 'function':
          $collection->add($doc->children(Filter::isFunction($object->name)));
          break;

        case 'function_call':
          $collection->add($doc->find(Filter::isFunctionCall($object->name)));
          break;

        case 'hook':
          $collection->add($doc->children(Filter::isFunction($this->target->id() . '_' . $object->name)));

        default:
          break;
      }
    }

    return $collection;
  }

  /**
   * {@inheritdoc}
   */
  public function add($object_type, $identifier, $file) {
    $this->db
      ->insert('dmu_index')
      ->fields([
        'module' => $this->target->id(),
        'type' => $object_type,
        'name' => $identifier,
        'file' => $file,
      ])
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function delete($object_type, $identifier = NULL) {
    $query = $this->db
      ->delete('dmu_index')
      ->condition('module', $this->target->id())
      ->condition('type', $object_type);

    if ($identifier) {
      $query->condition('name', $identifier);
    }

    $query->execute();
  }

}
