<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\VariableGet.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement;

use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\ScalarNode;
use Pharborist\StringNode;
use Symfony\Component\Yaml\Yaml;

/**
 * @Converter(
 *  id = "variable_get",
 *  description = @Translation("Replaces variable_get() calls with Configuration API."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2183531",
 *      "title" = @Translation("The Variable API has been removed")
 *    }
 *  },
 *  message = @Translation("<code>variable_get()</code> has been removed.")
 * )
 */
class VariableGet extends VariableBase {

  /**
   * The default configuration values for config/install/MODULE.settings.yml.
   *
   * @var array
   */
  private $config = [];

  /**
   * The configuration schema for config/schema/MODULE.settings.yml.
   *
   * @var array
   */
  private $mapping = [];

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    parent::convert($target);

    $module = $target->id();

    $schema = [
      $module . '.settings' => [
        'type' => 'mapping',
        'label' => 'Settings',
        'mapping' => $this->mapping,
      ],
    ];

    $this->write($target, 'config/install/' . $module . '.settings.yml', Yaml::dump($this->config, 4, 2));
    $this->write($target, 'config/schema/' . $module . '.schema.yml', Yaml::dump($schema, 4, 2));
  }

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    /** @var \Pharborist\ObjectMethodCallNode $config */
    $config = parent::rewrite($call)->appendMethodCall('get');

    $arguments = $call->getArguments();
    $config->appendArgument(clone $arguments[0]);

    // If there's a second argument, we can try to extract the default value.
    if (isset($arguments[1])) {
      // We can only extract the default value if the key is a constant scalar string.
      if ($arguments[0] instanceof StringNode) {
        $key = $arguments[0]->toValue();
        $value = NULL;

        // Furthermore, we can only extract the default value if it's a constant scalar value.
        // @todo Support arrays, as long as every array element is a constant scalar,
        // at every level of the array.
        if ($arguments[1] instanceof ScalarNode) {
          $value = $arguments[1]->toValue();
        }
        else {
          drush_log("Cannot extract default value of {$key} variable; using NULL.", 'warning');
        }

        $this->config[$key] = $value;
        $this->mapping[$key]['type'] = getType($value);
      }
      else {
        drush_log("Cannot extract default variable: invalid key {$arguments[0]}", 'warning');
      }
    }

    return $config;
  }

}
