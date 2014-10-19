<?php

namespace Drupal\drupalmoduleupgrader;

use Drupal\drupalmoduleupgrader\Converter\ConverterInterface;
use Pharborist\Node;

interface IssueInterface {

  /**
   * Returns the title of the issue.
   *
   * @return string
   */
  public function getTitle();

  /**
   * Sets the title of the issue.
   *
   * @param string $title
   *
   * @return $this
   */
  public function setTitle($title);

  /**
   * Returns the issue summary.
   *
   * @return string
   */
  public function getSummary();

  /**
   * Sets the issue summary.
   *
   * @param string $summary
   *
   * @return $this
   */
  public function setSummary($summary);

  /**
   * Adds a piece of documentation relevant to the issue.
   *
   * @param string $url
   *  The documentation's full URL.
   * @param string $title
   *  The documentation's displayed title.
   *
   * @return $this
   */
  public function addDocumentation($url, $title);

  /**
   * Returns all documentation as an array of arrays, each containing 'url'
   * and 'title' keys.
   *
   * @return array
   */
  public function getDocumentation();

  /**
   * Marks a particular file as being affected by this issue.
   *
   * @param string $file
   *  The path of the affected file.
   * @param \Drupal\drupalmoduleupgrader\Converter\ConverterInterface $detector
   *  The plugin which detected the problem.
   *
   * @return $this
   */
  public function addAffectedFile($file, ConverterInterface $detector);

  /**
   * Flags a single violation of this issue in a particular syntax node.
   *
   * @param \Pharborist\Node $node
   *  The offending syntax tree node.
   * @param \Drupal\drupalmoduleupgrader\Converter\ConverterInterface $detector
   *  The plugin which detected the violation.
   *
   * @return $this
   */
  public function addViolation(Node $node, ConverterInterface $detector);

  /**
   * Returns all violations as an array of arrays, each of which has a 'file' key
   * (required), and an optional 'line_number' key.
   *
   * @return array
   */
  public function getViolations();

  /**
   * Returns the fully qualified names of every plugin which detected violations,
   * as set by addAffectedFile() and addViolation().
   *
   * @return string[]
   */
  public function getDetectors();

  /**
   * Returns the error level.
   *
   * @return string
   */
  public function getErrorLevel();

  /**
   * Sets the error level, which is pretty much an arbitrary value. At the
   * time of this writing, it's only used as the CSS class of the issue
   * when the report is rendered.
   *
   * @param string $error_level
   *
   * @return $this
   */
  public function setErrorLevel($error_level);

}
