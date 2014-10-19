<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7\ParameterBinding.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing\Drupal7;

use Drupal\drupalmoduleupgrader\Converter\Routing\ParameterBindingBase;
use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\NodePropertyAccessRewriter;
use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\TermPropertyAccessRewriter;
use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\Entity\UserPropertyAccessRewriter;
use Drupal\drupalmoduleupgrader\Utility\Path\Drupal7\PathComponent;
use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;
use Pharborist\Functions\ParameterNode;

/**
 * Represents a binding between a single callback parameter and a single
 * path component in a Drupal 7 route path, possibly affected by an argument.
 */
class ParameterBinding extends ParameterBindingBase {

  /**
   * @var mixed
   */
  protected $argument;

  /**
   * The trouble with Drupal 7 callback arguments is that virtually any value
   * could be explicitly passed, including NULL and FALSE. -1 is an illegal
   * value because it's an integer, but not a valid path position. So we'll
   * use it here as a signal that no argument is explicitly bound to the
   * parameter.
   */
  const NO_ARGUMENT = -1;

  /**
   * {@inheritdoc}
   */
  public function __construct(PathUtilityInterface $path, ParameterNode $parameter, $argument = self::NO_ARGUMENT) {
    parent::__construct($path, $parameter);
    $this->argument = $argument;
  }

  /**
   * {@inheritdoc}
   */
  public function conform() {
    $value = $this->getValue();

    if ($value instanceof PathComponent && $value->isWildcard()) {
      $parameter = $this->getParameter();

      $entity_manager = \Drupal::entityManager();
      switch (ltrim($value, '%')) {
        case 'node':
          (new NodePropertyAccessRewriter($parameter, $entity_manager))->execute();
          break;

        case 'user':
          (new UserPropertyAccessRewriter($parameter, $entity_manager))->execute();
          break;

        case 'taxonomy_term':
          (new TermPropertyAccessRewriter($parameter, $entity_manager))->execute();
          break;

        default:
          break;
      }
    }

    parent::conform();
  }

  /**
   * Returns if this binding has an explicit argument.
   *
   * @return boolean
   */
  public function hasArgument() {
    return ($this->getArgument() !== self::NO_ARGUMENT);
  }

  /**
   * Returns the argument.
   *
   * @return mixed
   */
  public function getArgument() {
    return $this->argument;
  }

  /**
   * {@inheritdoc}
   */
  public function inPath() {
    return ($this->isPathPosition() && sizeof($this->path) > $this->getArgument());
  }

  /**
   * Whether or not the argument is a path position (integer greater
   * than or equal to 0).
   *
   * @return boolean
   */
  public function isPathPosition() {
    return ($this->hasArgument() && is_integer($this->getArgument()));
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    // So this is a fair bit o' logic. To start with, there are two basic
    // cases: either there is an argument in this binding, or there isn't.
    //
    // If argument:
    //  If argument is path position:
    //    If the position is physically in the path:
    //      Return the existing path component
    //    Else:
    //      Return new placeholder path component
    //  Else:
    //    Return verbatim argument
    // Else:
    //  Return parameter's default value.
    //
    if ($this->hasArgument()) {
      if ($this->isPathPosition()) {
        $position = $this->getArgument();
        return $this->path->containsKey($position) ? $this->path[$position] : new PathComponent('%');
      }
      else {
        return $this->getArgument();
      }
    }
    else {
        // @todo Only return the value if it's a static scalar (need such a filter
      // to be added to Pharborist)
      return eval('return ' . $this->getParameter()->getValue() . ';');
    }
  }

}
