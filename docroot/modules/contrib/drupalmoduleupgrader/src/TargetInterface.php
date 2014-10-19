<?php

namespace Drupal\drupalmoduleupgrader;

interface TargetInterface {

  /**
   * Returns the machine name of the target module.
   *
   * @return string
   */
  public function id();

  /**
   * Returns the base path of the target module.
   *
   * @return string
   */
  public function getBasePath();

  /**
   * Returns the path to a particular file, relative to the CWD.
   *
   * @param string $file
   *  The file, relative to the module root. If $file begins with a period,
   *  it will be prefixed with the module name (.module --> MODULE.module)
   *
   * @return string
   */
  public function getPath($file);

  /**
   * Returns a fully configured Finder which can iterate over the target
   * module's code files. Any file type which doesn't contain PHP code
   * should be ignored.
   *
   * @return \Symfony\Component\Finder\Finder
   */
  public function getFinder();

  /**
   * Returns a code manager for the target module.
   *
   * @return \Drupal\drupalmoduleupgrader\CodeManagerInterface
   */
  public function getCodeManager();

  /**
   * Returns a fully built indexer for the target module.
   *
   * @return \Drupal\drupalmoduleupgrader\Indexer\IndexerInterface
   */
  public function getIndexer();

  /**
   * Returns an issue report for the target module.
   *
   * @return \Drupal\drupalmoduleupgrader\ReportInterface
   */
  public function getReport();

  /**
   * Returns services defined by the target module.
   *
   * @return \Doctrine\Common\Collections\ArrayCollection
   */
  public function getServices();

}
