<?php

namespace Drupal\drupalmoduleupgrader;

use Pharborist\Node;

interface CodeManagerInterface {

  /**
   * Parses a file into a syntax tree, keeping a reference to it, and
   * returns it.
   *
   * @param string $file
   *  The path of the file to open, relative to the CWD.
   *
   * @return \Pharborist\TopNode|NULL
   */
  public function open($file);

  /**
   * Saves the file in which a particular node appears.
   *
   * @param \Pharborist\Node $node
   *  The node to save. This can be positioned anywhere in the
   *  syntax tree.
   *
   * @return boolean
   *  TRUE if the node was saved properly, FALSE otherwise.
   *
   * @throws \Drupal\drupalmoduleupgrader\CodeManagerIOException if the node
   * cannot be saved.
   */
  public function save(Node $node);

  /**
   * Saves all open files.
   */
  public function saveAll();

  /**
   * Creates a new, empty document.
   *
   * @param string $file
   *  The path of the file to create, relative to the CWD.
   *
   * @return \Pharborist\TopNode
   */
  public function create($file);

  /**
   * Clears internal references to all open documents, discarding changes.
   */
  public function flush();

}
