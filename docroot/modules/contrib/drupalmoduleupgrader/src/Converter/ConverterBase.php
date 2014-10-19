<?php

namespace Drupal\drupalmoduleupgrader\Converter;

use Drupal\Core\Plugin\PluginBase;
use Drupal\drupalmoduleupgrader\Issue;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\ClassNode;
use Pharborist\DocCommentNode;
use Pharborist\Functions\FunctionDeclarationNode;
use Pharborist\Parser;
use Pharborist\Filter;
use Pharborist\WhitespaceNode;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Base class for all converters.
 */
abstract class ConverterBase extends PluginBase implements ConverterInterface {

  /**
   * If this converter uses delegates, this is the delegate manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $delegator;

  /**
   * Identifiers for operations which have already been performed, in order
   * to prevent duplicate operations.
   *
   * @var string[]
   */
  protected static $processed = [];

  /**
   * Constructs a ConverterBase object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // If this converter uses delegates, get the delegates' plugin manager.
    if (isset($plugin_definition['delegator'])) {
      $this->delegator = \Drupal::service($plugin_definition['delegator']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    if (isset($this->pluginDefinition['message'])) {
      $issue = new Issue($target, $this->pluginDefinition['message']);

      if (isset($this->pluginDefinition['level'])) {
        $issue->setErrorLevel($this->pluginDefinition['level']);
      }

      foreach ($this->pluginDefinition['documentation'] as $doc) {
        $issue->addDocumentation($doc['url'], $doc['title']);
      }

      $target->getReport()->addIssue($issue);
    }
  }

  protected function implement(TargetInterface $target, $hook) {
    $function = FunctionDeclarationNode::create($target->id() . '_' . $hook);
    $function->setDocComment(DocCommentNode::create('Implements hook_' . $hook . '().'));

    $module_file = $target->getPath('.module');
    $target->getCodeManager()->open($module_file)->append($function);

    WhitespaceNode::create("\n")->insertBefore($function);
    WhitespaceNode::create("\n")->insertAfter($function);

    return $function;
  }

  public function write(TargetInterface $target, $path, $data) {
    static $fs;
    if (empty($fs)) {
      $fs = new Filesystem();
    }
    $fs->dumpFile($target->getPath($path), (string) $data);
  }

  public function writeClass(TargetInterface $target, ClassNode $class) {
    $class_path = ltrim($class->getName()->getAbsolutePath(), '\\');
    $path = str_replace([ 'Drupal\\' . $target->id(), '\\', ], [ 'src/', '/' ], $class_path);
    $this->write($target, $path . '.php', $class->parents()->get(0));
  }

  protected function writeInfo(TargetInterface $target, $group, array $data) {
    $destination = $target->getPath('.' . $group . '.yml');
    file_put_contents($destination, Yaml::dump($data, 4, 2));
  }

  /**
   * @param string $id
   *  The delegate plugin ID.
   * @param array $configuration
   *  Additional configuration to be handed to the delegate. The current module
   *  will be automatically added.
   *
   * @return object
   *  The delegate plugin instance.
   */
  protected function getDelegate($id, array $configuration = []) {
    $configuration['module'] = $this->module;
    return $this->delegator->createInstance($id, $configuration);
  }

  /**
   * Parses a generated class into a syntax tree.
   *
   * @param string|array $class
   *  The class to parse, either as a string of PHP code or a renderable array.
   *
   * @return \Pharborist\ClassNode
   */
  protected function parse($class) {
    if (is_array($class)) {
      $class = drupal_render($class);
    }
    return Parser::parseSnippet($class)->find(Filter::isInstanceOf('Pharborist\ClassNode'))[0];
  }

  /**
   * Gets or sets the status of this plugin (i.e., if it has already been
   * processed).
   *
   * @param bool|null $status
   *  If boolean, marks the plugin as processed or unprocessed.
   *
   * @return bool|null
   *  If $status is passed, no return value. Otherwise, returns the status of
   *  the plugin.
   */
  protected function isProcessed($status = NULL) {
    if (isset($status)) {
      if ($status) {
        static::$processed[] = $this->getHash();
      }
      else {
        $index = array_search($this->getHash(), static::$processed);
        if (is_integer($index)) {
          unset(static::$processed[$index]);
        }
      }
    }
    else {
      return in_array($this->getHash(), static::$processed);
    }
  }

  /**
   * Returns an arbitrary hash to identify this plugin instance/configuration.
   *
   * @return string
   */
  protected function getHash() {
    return $this->pluginId;
  }

}
