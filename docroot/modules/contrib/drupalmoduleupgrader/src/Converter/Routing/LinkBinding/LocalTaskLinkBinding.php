<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\LocalTaskLinkBinding.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding;

/**
 * Represents a local task or default local task.
 */
class LocalTaskLinkBinding extends LinkBinding {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $link = parent::build();

    $source = $this->getSource();

    if ($source->isDefaultLocalTask()) {
      $link['base_route'] = $link['route_name'];
    }
    elseif ($source->isLocalTask()) {
      $default_task = $source->getDefaultTask();
      if ($default_task) {
        $path = $default_task->getPath()->__toString();

        if ($this->index->containsKey($path)) {
          $link['base_route'] = $this->index[$path]->getDestination()->getIdentifier();
        }
      }
    }

    if ($source->hasParent()) {
      $parent = $source->getParent();

      if ($parent->isLocalTask() || $parent->isDefaultLocalTask()) {
        $parent_id = $this->getParentID();

        if ($parent_id) {
          unset($link['base_route']);
          $link['parent_id'] = $parent_id;
        }
      }
    }

    return $link;
  }

  /**
   * Gets the parent task's link ID, if any.
   *
   * @return string|NULL
   */
  public function getParentID() {
    $path = $this->getSource()->getParent()->getPath()->__toString();

    if ($this->index->containsKey($path)) {
      return $this->index[$path]->getIdentifier();
    }

    $parent = $this->getDestination()->getParent()->getIdentifier();

    $task_manager = \Drupal::service('plugin.manager.links.menu');
    foreach ($task_manager->getDefinitions() as $id => $link) {
      if ($link['route_name'] == $parent) {
        return $id;
      }
    }
  }

}
