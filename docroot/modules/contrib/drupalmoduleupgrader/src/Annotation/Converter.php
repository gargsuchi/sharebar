<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Annotation\Converter.
 */

namespace Drupal\drupalmoduleupgrader\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Plugin annotation object for DMU converter plugins.
 *
 * Plugin Namespace: Plugin\DMU
 *
 * @Annotation
 */
class Converter extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * A short description of the conversion the plugin performs.
   *
   * @var string
   */
  public $description;

  /**
   * Documentation describing the changes covered by the plugin. Each key can be
   * either a node ID on drupal.org, or a full URL. Each value is the displayed
   * title of the document.
   *
   * @var string[]
   */
  public $documentation = [];

  /**
   * The default (translated) message to be displayed when an issue is flagged.
   *
   * @var string
   */
  public $message;

  /**
   * The default error level of the changes covered by the plugin (used only when
   * generating a report).
   *
   * @var string
   */
  public $level = 'error';

  /**
   * If the plugin converts a hook, which hook it converts (without the hook_ prefix).
   *
   * @var string
   */
  public $hook;

  /**
   * If the plugin uses delegates, this should be the ID of the plugin manager service
   * for the delegate plugin type.
   *
   * @var string
   */
  public $delegator;

  /**
   * If the plugin should leave a FIXME notice near whatever it changes,
   * you can define it here.
   *
   * @var string
   */
  public $fixme;

  /**
   * The issue type, used to group violations detected by the plugin during
   * analysis. Defaults to the plugin ID.
   *
   * @var string
   */
  public $issue_type;

}
