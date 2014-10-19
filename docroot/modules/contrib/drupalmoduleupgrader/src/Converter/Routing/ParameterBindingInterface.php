<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Converter\Routing\ParameterBindingInterface.
 */

namespace Drupal\drupalmoduleupgrader\Converter\Routing;

use Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface;
use Pharborist\Functions\ParameterNode;

/**
 * Defines a binding between a callback function parameter and a path
 * component.
 */
interface ParameterBindingInterface {

  /**
   * Constructs a parameter binding.
   *
   * @param \Drupal\drupalmoduleupgrader\Utility\Path\PathUtilityInterface $path
   *  The path the parameter will be bound to.
   * @param \Pharborist\Functions\ParameterNode $parameter
   *  The parameter to bind.
   */
  public function __construct(PathUtilityInterface $path, ParameterNode $parameter);

  /**
   * Returns if the parameter is explicitly represented in the path.
   *
   * @return boolean
   */
  public function inPath();

  /**
   * The original parameter node.
   *
   * @return \Pharborist\Functions\ParameterNode
   */
  public function getParameter();

  /**
   * Returns the value of the binding. If the value is an instance of
   * \Drupal\drupalmoduleupgrader\Utility\Path\PathComponentInterface,
   * the binding expects to be physically represented in the path, although
   * it may not yet be (this can be ascertained by the inPath() method). Any
   * other value is used verbatim.
   *
   * @return mixed
   */
  public function getValue();

  /**
   * Sets another binding as a slave of this one. When this binding gets
   * a certain signal (TBD), the slave's parameter name and type hint will
   * be changed to conform to this one's.
   *
   * @param static $slave
   */
  public function enslave(ParameterBindingInterface $slave);

  /**
   * @param static $master
   *  The 'master' binding to be listened to for the conformance event.
   */
  public function setMaster(ParameterBindingInterface $master);

  /**
   * Conform the bound parameter to the master, if there is one.
   */
  public function conform();

}
