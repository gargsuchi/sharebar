<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookFormAlter.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionDeclarationNode;

/**
 * @Converter(
 *  id = "HookFormAlter",
 *  description = @Translation("Corrects hook_form_alter() function signatures."),
 *  documentation = {
 *    {
 *      "url" = "https://api.drupal.org/api/drupal/core%21modules%21system%21system.api.php/function/hook_form_alter/8",
 *      "title" = @Translation("<code>hook_form_alter()<code> documentation")
 *    }
 *  },
 *  message = @Translation("The signature of hook_form_alter() has changed in Drupal 8.")
 * )
 */
class HookFormAlter extends ConverterBase {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    // @todo
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $alter_hooks = $target->getIndexer()
      ->get('function')
      ->filter(function(FunctionDeclarationNode $function) use ($target) {
        return preg_match('/^' . $target->id() . '_form_*alter$/', $function->getName()->getText());
      });

    foreach ($alter_hooks as $alter_hook) {
      /** @var \Pharborist\Functions\ParameterNode[] $parameters */
      $parameters = $alter_hook->getParameters();

      if (sizeof($parameters) > 1) {
        $parameters[1]->setTypeHint('\Drupal\Core\Form\FormStateInterface');
      }
    }
  }

}
