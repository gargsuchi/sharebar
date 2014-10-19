<?php

use Drupal\drupalmoduleupgrader\Target;
use Drupal\drupalmoduleupgrader\Converter\CleanerInterface;

/**
 * Implements hook_drush_command().
 */
function drupalmoduleupgrader_drush_command() {
  $items = [];

  $items['dmu-list'] = [
    'description' => "Lists available analysis/conversion routines.",
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_ROOT,
  ];

  $items['dmu-analyze'] = [
    'description' => "Analyzes a Drupal 7 module and reports the changes needed to port it to Drupal 8.",
    'arguments' => [
      'module' => 'The machine name of a Drupal 7 module installed in the modules directory.',
    ],
    'required-arguments' => TRUE,
    'options' => [
      'only' => [
        'description' => 'A comma-separated list of analysis routines to run, excluding all others.',
        'example-value' => 'HookMenu,VariableAPI,BlockInfo',
      ],
      'skip' => [
        'description' => 'A comma-separated list of analysis routines to skip.',
        'example-value' => 'HookInit,HookExit',
      ],
      'path' => [
        'description' => 'Optional path to the target module.',
        'example-value' => 'drupal/modules/foobaz',
      ],
    ],
    'examples' => [
      'drush dmu-analyze pants' => 'Analyze what needs to be changed in order to port the pants module.',
    ],
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_ROOT,
  ];

  $items['dmu-upgrade'] = [
    'description' => "Upgrades a Drupal 7 module to Drupal 8.",
    'arguments' => [
      'module' => 'The machine name of a Drupal 7 module installed in the modules directory.',
    ],
    'required-arguments' => TRUE,
    'options' => [
      'only' => [
        'description' => 'A comma-separated list of conversion routines to run, excluding all others.',
        'example-value' => 'HookMenu,VariableAPI,BlockInfo',
      ],
      'skip' => [
        'description' => 'A comma-separated list of conversion routines to skip.',
        'example-value' => 'HookInit,HookExit',
      ],
      'path' => [
        'description' => 'Optional path to the target module. Will be determined automatically if omitted.',
        'example-value' => 'drupal/modules/foobaz',
      ],
      'clean' => [
        'description' => 'If set, dead code will be deleted from the source module.',
      ],
    ],
    'examples' => [
      'drush dmu-upgrade pants' => 'Upgrade whatever can be automatically upgraded in the pants module.',
    ],
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_ROOT,
  ];

  return $items;
}

/**
 * Loads all module-wide plugins.
 *
 * @return array
 */
function _dmu_plugin_list() {
  // Get the conversion priority list. Conversions should generally be written
  // in a way that allows them to run in any order, but certain ones, like Grep
  // and FunctionReplacement, need to run first. The priority array (defined in
  // config/install/drupalmoduleupgrader.settings.yml) lists the ones that should
  // run before any others, and the order in which they should be executed.
  $priority = \Drupal::config('drupalmoduleupgrader.settings')->get('priority');

  // Instantiate the plugin manager, get all available plugin IDs, and sort them
  // by the contents of the priority list.
  $manager = \Drupal::service('plugin.manager.drupalmoduleupgrader.module');
  $plugin_IDs = array_keys($manager->getDefinitions());
  $plugin_IDs = array_replace(array_flip($priority), array_flip($plugin_IDs));
  $plugin_IDs = array_keys($plugin_IDs);

  // If the user has specified a list of conversions to include or exclude,
  // filter the list of plugin IDs by that list.
  if ($only = drush_get_option('only', FALSE)) {
    $plugin_IDs = array_intersect($plugin_IDs, explode(',', $only));
  }
  elseif ($skip = drush_get_option('skip', FALSE)) {
    $plugin_IDs = array_diff($plugin_IDs, explode(',', $skip));
  }

  return $plugin_IDs;
}

/**
 * Lists all the available module-wide plugins.
 */
function drush_drupalmoduleupgrader_dmu_list() {
  $manager = \Drupal::service('plugin.manager.drupalmoduleupgrader.module');

  $list = [];
  foreach ($manager->getDefinitions() as $id => $definition) {
    $list[$id] = $definition['description'];
  }
  drush_print_table(drush_key_value_to_array_table($list));
}

/**
 * Checks for autoload.php, and includes it if it exists or sets an error
 * if it doesn't.
 */
function _dmu_ensure_autoload() {
  $autoload = __DIR__ . '/vendor/autoload.php';
  if (file_exists($autoload)) {
    require_once $autoload;
  }
  else {
    drush_set_error('no_autoload', 'autoload.php not found! Did you remember to run composer install?');
  }
}

function _dmu_get_directory($module) {
  if ($path = drush_get_option('path', NULL)) {
    return $path;
  }
  else {
    $search_directories = [
      DRUPAL_ROOT . '/modules/' . $module,
      __DIR__ . '/'. $module,
    ];

    $directories = array_filter($search_directories, 'is_dir');
    if ($directories) {
      return reset($directories);
    }
  }
}

/**
 * Checks possible locations of a target module, and ensures that at least
 * one exists. If none do, sets an error.
 *
 * @param string $module
 *  The target module's machine name.
 */
function _dmu_ensure_directory($module) {
  $directory = _dmu_get_directory($module);

  if (empty($directory)) {
    if ($path = drush_get_option('path', NULL)) {
      drush_set_error('invalid_dir', 'Invalid path: ' . $path);
    }
    else {
      drush_set_error('no_directory', "Cannot determine base directory of module $module. Try passing --path=modules/foobar");
    }
  }
}

function drush_drupalmoduleupgrader_dmu_analyze_validate($module) {
  _dmu_ensure_autoload();
  _dmu_ensure_directory($module);
}

/**
 * Analyzes what needs changing in a module to port it to Drupal 8.
 *
 * @param string $module
 *  The machine name of the module to analyze.
 */
function drush_drupalmoduleupgrader_dmu_analyze($module) {
  $target = new Target(_dmu_get_directory($module), \Drupal::getContainer());
  $plugin_manager = \Drupal::service('plugin.manager.drupalmoduleupgrader.module');

  foreach (_dmu_plugin_list() as $plugin_id) {
    $plugin = $plugin_manager->createInstance($plugin_id);

    if ($plugin->isExecutable($target)) {
      $plugin->analyze($target);
    }
  }

  $render = [
    '#theme' => 'dmu_report',
    '#report' => $target->getReport(),
  ];
  file_put_contents($target->getPath('upgrade-info.html'), render($render));

  drush_log('Generated a report in ' . $target->getPath('upgrade-info.html'), 'success');
  $target->getIndexer()->clear();
}

function drush_drupalmoduleupgrader_dmu_upgrade_validate($module) {
  _dmu_ensure_autoload();
  _dmu_ensure_directory($module);
}

/**
 * Tries to automatically convert a Drupal 7 module to Drupal 8.
 *
 * @param string $module
 *  The module to upgrade.
 */
function drush_drupalmoduleupgrader_dmu_upgrade($module) {
  $target = new Target(_dmu_get_directory($module), \Drupal::getContainer());
  $plugin_manager = \Drupal::service('plugin.manager.drupalmoduleupgrader.module');

  foreach (_dmu_plugin_list() as $plugin_id) {
    $plugin = $plugin_manager->createInstance($plugin_id);

    if ($plugin->isExecutable($target)) {
      try {
        $plugin->convert($target);

        if (drush_get_option('clean', FALSE) && $plugin instanceof CleanerInterface) {
          $plugin->clean($target);
        }
      }
      catch (\Exception $e) {
        drush_log('[' . $plugin->getPluginId() . '] ' . get_class($e) . ': ' . $e->getMessage(), 'error');
        drush_log($e->getTraceAsString(), 'error');
      }
    }
  }

  $target->getIndexer()->clear();
}
