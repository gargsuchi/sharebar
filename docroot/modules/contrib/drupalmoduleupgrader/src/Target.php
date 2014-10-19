<?php

namespace Drupal\drupalmoduleupgrader;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class Target implements TargetInterface {

  /**
   * The target module's machine name.
   *
   * @var string
   */
  protected $id;

  /**
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $container;

  /**
   * The target module's base path.
   *
   * @var string
   */
  protected $basePath;

  /**
   * @var \Drupal\drupalmoduleupgrader\CodeManagerInterface
   */
  protected $codeManager;

  /**
   * @var \Drupal\drupalmoduleupgrader\Indexer\IndexerInterface
   */
  protected $indexer;

  /**
   * @var \Drupal\drupalmoduleupgrader\ReportInterface
   */
  protected $report;

  /**
   * @var \Doctrine\Common\Collections\ArrayCollection
   */
  protected $services;

  /**
   * @param string $path
   *  The base path of the target module.
   */
  public function __construct($path, ContainerInterface $container) {
    $this->container = $container;

    if (is_dir($path)) {
      $this->basePath = $path;
    }
    else {
      throw new \RuntimeException('Invalid base path: ' . $path);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function id() {
    if (empty($this->id)) {
      $info = (new Finder())
        ->in($this->getBasePath())
        ->depth('== 0')
        ->name('*.info')
        ->getIterator();
      $info->rewind();
      $this->id = subStr($info->current()->getFilename(), 0, -5);
    }
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * {@inheritdoc}
   */
  public function getPath($file) {
    if ($file{0} == '.') {
      $file = $this->id() . $file;
    }
    return $this->getBasePath() . '/' . ltrim($file, '/');
  }

  /**
   * {@inheritdoc}
   */
  public function getFinder() {
    // We do NOT want to include submodules. We can detect one by the presence of an
    // info file -- if there is one, its directory is a submodule.
    $directories = (new Finder())
      ->directories()
      ->in($this->getBasePath())
      ->filter(function(\SplFileInfo $dir) {
        return (new Finder())->files()->in($dir->getPath())->depth('== 0')->name('*.info')->count() === 0;
      });

    $directories = array_keys(iterator_to_array($directories));
    $directories[] = $this->getBasePath();

    return (new Finder())
      ->in($directories)
      // We don't need to recurse, because we've already determined which
      // directories to search.
      ->depth('== 0')
      ->name('*.module')
      ->name('*.install')
      ->name('*.inc')
      ->name('*.php')
      ->name('*.test');
  }

  /**
   * {@inheritdoc}
   */
  public function getCodeManager() {
    if (empty($this->codeManager)) {
      $this->codeManager = new CodeManager();
    }
    return $this->codeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getIndexer() {
    if (empty($this->indexer)) {
      $this->indexer = $this->container->get('module_indexer')->index($this);
    }
    return $this->indexer;
  }

  /**
   * {@inheritdoc}
   */
  public function getReport() {
    if (empty($this->report)) {
      $this->report = new Report();
    }
    return $this->report;
  }

  /**
   * {@inheritdoc}
   */
  public function getServices() {
    if (empty($this->services)) {
      $this->services = new ArrayCollection();
    }
    return $this->services;
  }

}
