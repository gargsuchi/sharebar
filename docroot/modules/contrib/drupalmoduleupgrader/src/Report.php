<?php

namespace Drupal\drupalmoduleupgrader;

class Report implements ReportInterface {

  /**
   * @var \Drupal\drupalmoduleupgrader\IssueInterface[]
   */
  protected $issues = [];

  /**
   * {@inheritdoc}
   */
  public function addIssue(IssueInterface $issue) {
    $id = spl_object_hash($issue);
    $this->issues[$id] = $issue;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIssues() {
    // We call array_values() here to reset the keys.
    return array_values($this->issues);
  }

}
