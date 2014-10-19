<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\Grep.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\Core\Config\Config;
use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Converter(
 *  id = "Grep",
 *  description = @Translation("Searches for and replaces commonly-used code that has changed in Drupal 8."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2067859",
 *      "title" = @Translation("<code>drupal_get_title()</code> and <code>drupal_set_title()</code> were removed")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2181921",
 *      "title" = @Translation("<code>drupal_cron_run()</code> was moved into the cron service")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2219113",
 *      "title" = @Translation("<code>drupal_json_encode</code> and <code>drupal_json_decode</code> were moved"),
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2295037",
 *      "title" = @Translation("<code>drupal_is_cli()</code> replaced by <code>PHP_SAPI</code> constant")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2302541",
 *      "title" = @Translation("<code>drupal_valid_path()</code> was moved into the PathValidator service")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2324935",
 *      "title" = @Translation("The global theme variables have been replaced by an ActiveTheme object")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2328179",
 *      "title" = @Translation("<code>_node_revision_access()</code> replaced by a service")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2173683",
 *      "title" = @Translation("The <code>element_*</code> functions have been replaced by methods of the Element class")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2173787",
 *      "title" = @Translation("<code>format_plural()</code> was moved into the translation service")
 *    },
 *    {
 *      "url" = "https://www.drupal.org/node/2328205",
 *      "title" = @Translation("Certain Taxonomy API functions have moved into the TermStorage class.")
 *    }
 *  },
 *  message = @Translation("Many common functions, shared variables, and constants have been renamed."),
 *  level = "warning"
 * )
 */
class Grep extends ConverterBase {

  /**
   * @var string[]
   */
  private $targets = [];

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Config $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    foreach ($config->get('globals') as $variable => $replacement) {
      $this->targets['global $' . $variable] = '$' . $variable . ' = ' . $replacement;
      $this->targets['$GLOBALS[\'' . $variable . '\']'] = $replacement;
      $this->targets['$GLOBALS["' . $variable . '"]'] = $replacement;
    }
    foreach ($config->get('function_calls') as $call => $replacement) {
      $this->targets[$call . '('] = $replacement . '(';
    }
    foreach ($config->get('constants') as $constant => $replacement) {
      $this->targets[$constant] = $replacement;
    }
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('config.factory')->get('drupalmoduleupgrader.grep'));
  }

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    foreach ($target->getFinder() as $file) {
      // Load in the entire contents of the module. This is criminally inefficient
      // and wasteful of memory and should eventually be refactored into something
      // a little more...I dunno, sustainable.
      /** @var \SplFileInfo $file */
      $data = file_get_contents($file->getPathname());
      $search = array_keys($this->targets);
      $replace = array_values($this->targets);
      file_put_contents($file->getPathname(), str_replace($search, $replace, $data));
    }
  }

}
