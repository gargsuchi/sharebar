<?php

namespace Drupal\drupalmoduleupgrader;

use Drupal\drupalmoduleupgrader\Converter\ConverterInterface;
use Pharborist\Node;

class Issue implements IssueInterface {

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  protected $target;

  /**
   * @var string
   */
  protected $title;

  /**
   * @var string
   */
  protected $summary;

  /**
   * @var array
   */
  protected $documentation = [];

  /**
   * @var array
   */
  protected $violations = [];

  /**
   * @var \Drupal\drupalmoduleupgrader\Converter\ConverterInterface[]
   */
  protected $detectors = [];

  /**
   * @var string
   */
  protected $errorLevel = 'error';

  public function __construct(Target $target, $title = NULL, $summary = NULL) {
    $this->target = $target;

    if (isset($title)) {
      $this->setTitle($title);
    }
    if (isset($summary)) {
      $this->setSummary($summary);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->title = (string) $title;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return $this->summary;
  }

  /**
   * {@inheritdoc}
   */
  public function setSummary($summary) {
    $this->summary = (string) $summary;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addDocumentation($url, $title) {
    $this->documentation[] = [
      'url' => $url,
      'title' => $title,
    ];
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentation() {
    return $this->documentation;
  }

  /**
   * {@inheritdoc}
   */
  public function addAffectedFile($file, ConverterInterface $detector) {
    if (empty($this->violations[$file])) {
      $this->violations[$file] = [];
    }
    $this->addDetector($detector);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addViolation(Node $node, ConverterInterface $detector) {
    $file = $this->target->getCodeManager()->getFileOf($node);
    if ($file) {
      $this->violations[$file][] = [
        'line_number' => $node->getSourcePosition()->getLineNumber(),
      ];
    }
    else {
      throw new \DomainException('Cannot record an issue violation from a detached node.');
    }

    $this->addDetector($detector);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getViolations() {
    $return_violations = [];

    foreach ($this->violations as $file => $file_violations) {
      if ($file_violations) {
        foreach ($file_violations as $violation) {
          $violation['file'] = $file;
          $return_violations[] = $violation;
        }
      }
      else {
        $return_violations[] = ['file' => $file];
      }
    }

    return $return_violations;
  }

  /**
   * {@inheritdoc}
   */
  public function getDetectors() {
    return array_map('get_class', $this->detectors);
  }

  /**
   * {@inheritdoc}
   */
  public function getErrorLevel() {
    return $this->errorLevel;
  }

  /**
   * {@inheritdoc}
   */
  public function setErrorLevel($error_level) {
    $this->errorLevel = $error_level;
    return $this;
  }

  /**
   * Stores a reference to an issue detector, if we don't already know about it,
   * for use by getDetectors().
   *
   * @param \Drupal\drupalmoduleupgrader\Converter\ConverterInterface $detector
   */
  protected function addDetector(ConverterInterface $detector) {
    if (! in_array($detector, $this->detectors, TRUE)) {
      $this->detectors[] = $detector;
    }
  }

}
