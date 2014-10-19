<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\PSR4.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\Converter\IndexerInterface;
use Drupal\drupalmoduleupgrader\ModuleContext;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\ClassNode;
use Pharborist\Filter;
use Pharborist\NodeCollection;
use Pharborist\TopNode;
use Pharborist\WhitespaceNode;

/**
 * @Converter(
 *  id = "PSR4",
 *  description = @Translation("Moves classes into PSR-4 directory structure."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/2246699",
 *      "title" = @Translation("PSR-4 compatible class loader added to Drupal core")
 *    }
 *  },
 *  message = @Translation("Classes must be PSR-4 compliant.")
 * )
 */
class PSR4 extends ConverterBase implements IndexerInterface {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    // You could almost say: "Is this target classy enough to work with?"
    return $target->getIndexer()->has('class');
  }

  /**
   * {@inheritdoc}
   */
  public function index(TargetInterface $target) {
    $indexer = $target->getIndexer();

    foreach ($target->getFinder() as $file) {
      /** @var \SplFileInfo $file */
      $target
        ->getCodeManager()
        ->open($file->getPathname())
        ->children(Filter::isInstanceOf('\Pharborist\Functions\ClassNode'))
        ->not(function(ClassNode $class) {
          // If there is no parent class, getExtends() will return NULL, so
          // casting to string will prevent 'call to getName() method of a
          // non-object' errors.
          return preg_match('/TestCase$/', (string) $class->getExtends());
        })
        ->each(function(ClassNode $class) use ($file, $indexer) {
          $indexer->add('class', $class->getName()->getText(), $file->getPathname());
        });
    }
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $targets = $target
      ->getIndexer()
      ->get('class')
      ->not(function(ClassNode $class) {
        // Don't handle tests -- we've got a separate plugin for that.
        // We need to use the (string) cast on getExtends() because it may return
        // NULL if the class is not extending another.
        return preg_match('/TestCase$/', (string) $class->getExtends());
      });

    foreach ($targets as $class) {
      $this->writeClass($target, self::toPSR4($target, $class));
    }
  }

  /**
   * Utility method to PSR4-ify a class. It'll move the class into its own file
   * in the given module's namespace. The class is modified in-place, so you
   * should clone it before calling this function if you want to make a PSR-4
   * *copy* of it.
   *
   * @param \Drupal\drupalmoduleupgrader\ModuleContext $module
   *  The module which will own the class.
   * @param \Pharborist\ClassNode $class
   *  The class to modify.
   *
   * @return \Pharborist\ClassNode
   *  The modified class, returned for convenience.
   */
  public static function toPSR4(TargetInterface $target, ClassNode $class) {
    $ns = 'Drupal\\' . $target->id();
    TopNode::create($ns)->getNamespace($ns)->append($class->remove());
    WhitespaceNode::create("\n\n")->insertBefore($class);

    return $class;
  }

}
