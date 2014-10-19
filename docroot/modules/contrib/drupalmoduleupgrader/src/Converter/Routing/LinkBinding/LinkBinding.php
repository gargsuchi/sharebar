<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding\LinkBinding.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\LinkBinding;

use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper as Drupal7Route;
use Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\RouteWrapper as Drupal8Route;
use Drupal\drupalmoduleupgrader\Converter\Routing\LinkIndex;

/**
 * Represents a binding between a Drupal 7 route and a Drupal 8 one.
 */
class LinkBinding {

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper
   */
  protected $source;

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\RouteWrapper
   */
  protected $destination;

  /**
   * The link ID.
   *
   * @var string
   */
  protected $id;

  /**
   * Index of all other links of this type.
   *
   * @var \Drupal\drupalmoduleupgrader\Converter\Routing\LinkIndex
   */
  protected $index;

  /**
   * Constructs a LinkBinding object.
   */
  public function __construct(Drupal7Route $source, Drupal8Route $destination) {
    $this->source = $source;
    $this->destination = $destination;
  }

  /**
   * Returns the Drupal 7 route in this binding.
   *
   * @return \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * Returns the Drupal 8 route in this binding.
   *
   * @return \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper
   */
  public function getDestination() {
    return $this->destination;
  }

  /**
   * Returns the link's plugin ID.
   *
   * @return string
   */
  public function getIdentifier() {
    return isset($this->id) ? $this->id : $this->getDestination()->getIdentifier();
  }

  /**
   * React when the binding is added to an index.
   *
   * @param string $id
   *  The link's plugin ID, sanitized to prevent collisions.
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\LinkIndex $index
   *  The link index.
   */
  public function onIndexed($id, LinkIndex $index) {
    $this->id = $id;
    $this->index = $index;
  }

  /**
   * Builds the link definition.
   *
   * @return array
   */
  public function build() {
    $link = [
      'route_name' => $this->getDestination()->getIdentifier(),
    ];

    $source = $this->getSource();
    if ($source->containsKey('title')) {
      $link['title'] = $source['title'];
    }
    if ($source->containsKey('weight')) {
      $link['weight'] = $source['weight'];
    }

    return $link;
  }

  /**
   * Factory method. Returns a link binding object appropriate for the source link type.
   *
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\RouteWrapper $source
   *  The source (Drupal 7) route.
   * @param \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal8\RouteWrapper $destination
   *  The destination (Drupal 8) route.
   *
   * @return mixed
   *  A link binding object; either an instance of this class or a subclass thereof.
   */
  public static function create(Drupal7Route $source, Drupal8Route $destination) {
    if ($source->isLink()) {
      return new MenuLinkBinding($source, $destination);
    }
    elseif ($source->isLocalTask() || $source->isDefaultLocalTask()) {
      return new LocalTaskLinkBinding($source, $destination);
    }
    elseif ($source->isLocalAction()) {
      if ($source->isContextualLink()) {
        return new static($source, $destination);
      }
      else {
        return new LocalActionLinkBinding($source, $destination);
      }
    }
  }

}
