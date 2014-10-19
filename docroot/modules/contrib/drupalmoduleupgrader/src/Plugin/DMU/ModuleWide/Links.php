<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Links.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu;
use Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\LinkBinding;
use Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\LocalActionLinkBinding;
use Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\LocalTaskLinkBinding;
use Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\MenuLinkBinding;
use Drupal\drupalmoduleupgrader\Converter\Routing\LinkIndex;
use Drupal\drupalmoduleupgrader\Parser\ParserInterface;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Converter(
 *  id = "Links",
 *  description = @Translation("Converts Drupal 7's hook_menu() links to plugin definitions."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2165243",
 *      "title" = @Translation("Contextual links are now plugins defined in <kbd>MODULE.links.contextual.yml</kbd>")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2007444",
 *      "title" = @Translation("Local actions are now plugins defined in <kbd>MODULE.links.action.yml</kbd>")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2044515",
 *      "title" = @Translation("Local tasks and now plugins defined in <kbd>MODULE.links.task.yml</kbd>")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2228089",
 *      "title" = @Translation("Menu links are now defined in <kbd>MODULE.links.menu.yml</kbd>")
 *    }
 *  },
 *  message = @Translation("hook_menu() has been replaced by a completely new routing and linking system."),
 *  hook = "menu"
 * )
 */
class Links extends HookConverterBase {

  /**
   * The hook_menu() converter.
   *
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu
   */
  protected $hookMenu;

  /**
   * Constructs a Links object.
   *
   * @param array $configuration
   *   Additional configuration for the plugin.
   * @param string $plugin_id
   *   The plugin ID, will be "Links".
   * @param string $plugin_definition
   *   The plugin definition as derived from the annotations.
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\HookMenu $hook_menu
   *   The hook_menu() converter.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, HookMenu $hook_menu) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->hookMenu = $hook_menu;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      new HookMenu($container->get('plugin.manager.drupalmoduleupgrader.route'))
    );
  }

  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    // @todo Flag appropriate messages depending on what link types are present
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $links = [
      'menu' => new LinkIndex(),
      'task' => new LinkIndex(),
      'action' => new LinkIndex(),
      'contextual' => new LinkIndex(),
    ];

    foreach ($this->hookMenu->getSourceRoutes($target)->getAllLinks() as $path => $source) {
      /** @var LinkBinding $binding */
      $binding = LinkBinding::create($source, $this->hookMenu->getDestinationRoute($path));

      // Skip if the converter wasn't able to find a destination.
      $destination = $binding->getDestination();
      if (empty($destination)) {
        continue;
      }

      if ($binding instanceof MenuLinkBinding) {
        $links['menu']->addBinding($binding);
      }
      elseif ($binding instanceof LocalTaskLinkBinding) {
        $links['task']->addBinding($binding);
      }
      elseif ($binding instanceof LocalActionLinkBinding) {
        $links['action']->addBinding($binding);
      }
      elseif ($source->isContextualLink()) {
        $links['contextual']->addBinding($binding);
      }
    }

    $links = array_map(function(LinkIndex $index) {
      return $index->build();
    }, $links);

    foreach ($links['contextual'] as $link) {
      $link['group'] = $target->id();
    }

    foreach ($links as $group => $data) {
      if ($data) {
        $this->writeInfo($target, 'links.' . $group, $data);
      }
    }
  }

}
