<?php

namespace Drupal\drupalmoduleupgrader;

interface ReportInterface {

  /**
   * Adds an issue to this module.
   *
   * @param \Drupal\drupalmoduleupgrader\IssueInterface $issue
   *
   * @return $this
   */
  public function addIssue(IssueInterface $issue);

  /**
   * Returns all issues collected so far.
   *
   * @return \Drupal\drupalmoduleupgrader\IssueInterface[]
   */
  public function getIssues();

}
