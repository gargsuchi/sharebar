<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookMenuAlter.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\DocCommentNode;
use Pharborist\Functions\FunctionDeclarationNode;
use Pharborist\Functions\ParameterNode;
use Pharborist\WhitespaceNode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Converter(
 *  id = "HookMenuAlter",
 *  description = @Translation("Creates boilerplate for logic that formerly belonged in hook_menu_alter()."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2118147#alter",
 *      "title" = @Translation("Replacements for <code>hook_menu_alter()</code>")
 *    }
 *  },
 *  message = @Translation("hook_menu_alter() has been removed."),
 *  hook = "menu_alter",
 *  fixme = @Translation("hook_menu_alter() is gone in Drupal 8. You will have to port its
functionality manually. The are several mechanisms for this:

To alter routes, you must implement a route subscriber class. One has been
generated for you in src/Routing/RouteSubscriber.php.

To alter menu link definitions, see hook_menu_links_discovered_alter(). An
empty implementation has been created at the end of this file.

To alter local task definitions, see hook_menu_local_tasks_alter(). An
empty implementation has been created for you at the end of this file.

To alter local actions, see hook_menu_local_actions_alter(). An
empty implementation has been created for you at the end of this file.

Contextual links are altered during rendering only. See
hook_contextual_links_view_alter(). An empty implementation has been
created for you at the end of this file.

More information about the replacements for hook_menu_alter() can be
found at https://www.drupal.org/node/2118147#alter")
 * )
 */
class HookMenuAlter extends HookConverterBase {

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $render = [
      '#theme' => 'dmu_route_subscriber',
      '#module' => $target->id(),
    ];
    $this->writeClass($target, $this->parse($render));

    $alterable = ParameterNode::create('data');
    $alterable->setTypeHint('array')->setReference(TRUE);

    $parameter = clone $alterable;
    $this
      ->implement($target, 'menu_links_discovered_alter')
      ->appendParameter($parameter->setName('links'));

    $parameter = clone $alterable;
    $this
      ->implement($target, 'menu_local_tasks_alter')
      ->appendParameter($parameter->setName('data'))
      ->appendParameter(ParameterNode::create('route_name'));

    $parameter = clone $alterable;
    $this
      ->implement($target, 'menu_local_actions_alter')
      ->appendParameter($parameter->setName('local_actions'));

    $parameter = clone $alterable;
    $items = clone $alterable;
    $function = $this
      ->implement($target, 'contextual_links_view_alter')
      ->appendParameter($parameter->setName('element'))
      ->appendParameter($items->setName('items')->setReference(FALSE));

    // $this->addFixMe();
    $target->getCodeManager()->save($function);
  }

}
