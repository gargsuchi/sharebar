<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\LocalActionLinkBinding.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding;

/**
 * Represents a local action.
 */
class LocalActionLinkBinding extends LinkBinding {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $link = parent::build();
    $link['appears_on'][] = $this->getDestination()->getIdentifier();

    return $link;
  }

}
