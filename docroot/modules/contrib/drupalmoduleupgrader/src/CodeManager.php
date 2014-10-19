<?php

namespace Drupal\drupalmoduleupgrader;

use Pharborist\Node;
use Pharborist\Parser;
use Pharborist\TopNode;

class CodeManager implements CodeManagerInterface {

  /**
   * All open documents.
   *
   * @var \Pharborist\TopNode[]
   */
  protected $documents = [];

  /**
   * {@inheritdoc}
   */
  public function open($file) {
    if (empty($this->documents[$file])) {
      $this->documents[$file] = Parser::parseFile($file);
    }
    return $this->documents[$file];
  }

  /**
   * {@inheritdoc}
   */
  public function save(Node $node) {
    $root = $node instanceof TopNode ? $node : $node->parents()[0];

    foreach ($this->documents as $file => $doc) {
      if ($doc === $root) {
        return (boolean) file_put_contents($file, $root->getText());
      }
    }
    throw new CodeManagerIOException('Cannot save a node that is not attached to an open document.');
  }

  /**
   * {@inheritdoc}
   */
  public function saveAll() {
    foreach ($this->documents as $file => $doc) {
      file_put_contents($file, $doc->getText());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function create($file, $ns = NULL) {
    $this->documents[$file] = TopNode::create($ns);
    return $this->documents[$file];
  }

  /**
   * {@inheritdoc}
   */
  public function flush() {
    $this->documents = [];
  }

  public function getFileOf(Node $node) {
    if ($node instanceof TopNode) {
      $root = $node;
    }
    else {
      $parents = $node->parents();
      if ($parents->isEmpty()) {
        return NULL;
      }
      $root = $parents->get(0);
    }

    foreach ($this->documents as $file => $doc) {
      if ($root === $doc) {
        return $file;
      }
    }
  }

}
