<?php

namespace Drupal\drupalmoduleupgrader\Indexer;

/**
 * Defines an interface for indexing a module's code.
 */
interface IndexerInterface {

  /**
   * Builds the index.
   */
  public function build();

  /**
   * Clears the index.
   */
  public function clear();

  /**
   * Tests if the index has a specific object, or any objects of a
   * specific type.
   *
   * @param string $object_type
   * @param string $identifier
   *
   * @return boolean
   */
  public function has($object_type, $identifier = NULL);

  /**
   * Counts the number of objects of a certain type and/or identifier.
   *
   * @param string $object_type
   * @param string|NULL $identifier
   *
   * @return integer
   */
  public function count($object_type, $identifier = NULL);

  /**
   * Loads a specific object, or every object of a specific type, from the
   * index.
   *
   * @param string $object_type
   * @param string|NULL $identifier
   *
   * @return \Pharborist\NodeCollection
   */
  public function get($object_type, $identifier = NULL);

  /**
   * Adds an object the index.
   *
   * @param string $object_type
   * @param string $identifier
   * @param string $file
   *  The file in which the object appears.
   */
  public function add($object_type, $identifier, $file);

  /**
   * Deletes an object from the index.
   *
   * @param string $object_type
   * @param string|NULL $identifier
   *  If no identifier is given, every $object_type will be deleted.
   */
  public function delete($object_type, $identifier = NULL);

}
