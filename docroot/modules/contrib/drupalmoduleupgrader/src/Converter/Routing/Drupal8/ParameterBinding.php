<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\ParameterBinding.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8;

use Drupal\drupalmoduleupgrader\Converter\Routing\ParameterBindingBase;

/**
 * Represents a binding between a single callback parameter and a single
 * path component in a Drupal 8 route path, possibly affected by an argument.
 */
class ParameterBinding extends ParameterBindingBase {

  /**
   * {@inheritdoc}
   */
  public function inPath() {
    return $this->path->contains('{' . $this->getParameter()->getName() . '}');
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    $components = $this->path->find('{' . $this->getParameter()->getName() . '}');

    if (sizeof($components) > 0) {
      return $components->first();
    }
    else {
      return eval('return ' . $this->getParameter()->getValue() . ';');
    }
  }

}
