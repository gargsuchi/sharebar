<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\ParameterBindingBase.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing;

use Drupal\drupalmoduleupgrader\Utility\ParametricRewriter\ParametricRewriterBase;
use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;
use Pharborist\Functions\ParameterNode;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Represents a binding between a single callback parameter and a single
 * path component in a Drupal 8 route path, possibly affected by an argument.
 */
abstract class ParameterBindingBase implements ParameterBindingInterface {

  /**
   * @var \Drupal\drupalmoduleupgrader\Utility\Drupal8\PathUtility
   */
  protected $path;

  /**
   * @var \Pharborist\Functions\ParameterNode
   */
  protected $parameter;

  /**
   * The 'master' binding for this one, which may be listened to for
   * the conformance event.
   *
   * @var static
   */
  protected $master;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcher
   */
  protected $dispatcher;

  const CONFORMANCE_EVENT = 'parameter.conform';

  /**
   * {@inheritdoc}
   */
  public function __construct(PathUtilityInterface $path, ParameterNode $parameter) {
    // Clone $path so that we have our own copy to look at. The original $path
    // is (probably) modified by upstream code.
    $this->path = clone $path;
    $this->parameter = $parameter;
    $this->dispatcher = new EventDispatcher();
  }

  /**
   * {@inheritdoc}
   */
  public function getParameter() {
    return $this->parameter;
  }

  /**
   * {@inheritdoc}
   */
  public function enslave(ParameterBindingInterface $slave) {
    $slave->setMaster($this);
    $this->dispatcher->addListener(self::CONFORMANCE_EVENT, [ $slave, 'conform' ]);
  }

  /**
   * {@inheritdoc}
   */
  public function setMaster(ParameterBindingInterface $master) {
    $this->master = $master;
  }

  /**
   * {@inheritdoc}
   */
  public function conform() {
    if ($this->master) {
      (new ParametricRewriterBase($this->getParameter()))->match($this->master->getParameter());
    }

    // Propagate the event to the slaves.
    $this->dispatcher->dispatch(self::CONFORMANCE_EVENT);
  }

}
