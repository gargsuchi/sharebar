<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\CTools\CToolsGetPlugins.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\FunctionReplacement\CTools;

use Drupal\drupalmoduleupgrader\Converter\FunctionReplacement\FunctionCallRewriter;
use Pharborist\ClassMethodCallNode;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Node;
use Pharborist\StringNode;

/**
 * @Converter(
 *  id = "ctools_get_plugins",
 *  description = @Translation("Converts calls to ctools_get_plugins()."),
 *  documentation = {
 *    {
 *      "url" = "https://api.drupal.org/api/drupal/core%21modules%21system%21core.api.php/group/plugin_api/8",
 *      "title" = @Translation("Drupal 8 plugin system documentation")
 *    }
 *  },
 *  message = @Translation("The CTools plugin system has been moved into Drupal core.")
 * )
 */
class CToolsGetPlugins extends FunctionCallRewriter {

  /**
   * {@inheritdoc}
   */
  public function canRewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();
    return ($arguments[0] instanceof StringNode && $arguments[0]->toValue() == $this->module->getMachineName() && $arguments[1] instanceof StringNode);
  }

  /**
   * {@inheritdoc}
   */
  public function rewrite(FunctionCallNode $call) {
    $arguments = $call->getArguments();
    $plugin_owner = $arguments[0]->toValue();
    $plugin_type = $arguments[1]->toValue();

    $serviceID = 'plugin.manager.' . $plugin_owner . '.' . $plugin_type;
    $this->target->getServices()->set($serviceID, [
      'class' => 'Drupal\Core\Plugin\DefaultPluginManager',
      'arguments' => [
        'Plugin/' . $plugin_owner . '/' . $plugin_type,
        '@container.namespaces',
        '@module_handler',
        'Drupal\Component\Plugin\PluginBase',
        'Drupal\Component\Annotation\Plugin',
      ],
    ]);
    $this->writeInfo($this->target, 'services', [ 'services' => $this->target->getServices()->toArray() ]);

    return ClassMethodCallNode::create('\Drupal', 'service')
      ->appendArgument(StringNode::fromValue($serviceID))
      ->appendMethodCall('getDefinitions');
  }

}
