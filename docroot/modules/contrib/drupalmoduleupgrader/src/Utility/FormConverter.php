<?php

/**
 * @file
 * Contains \Drupal\drupalmoduleupgrader\Utility\FormConverter.
 */

namespace Drupal\drupalmoduleupgrader\Utility;

use Drupal\drupalmoduleupgrader\TargetInterface;
use Pharborist\Filter;
use Pharborist\Functions\FunctionCallNode;
use Pharborist\Functions\FunctionDeclarationNode;
use Pharborist\Functions\ParameterNode;
use Pharborist\ClassNode;
use Pharborist\Parser;
use Pharborist\Token;
use Pharborist\TokenNode;

/**
 * Converts a form from a set of callback functions to a class implementing
 * \Drupal\Core\Form\FormInterface.
 */
class FormConverter {

  use StringTransformTrait;

  /**
   * @var \Drupal\drupalmoduleupgrader\TargetInterface
   */
  protected $target;

  /**
   * The form builder function.
   *
   * @var \Pharborist\Functions\FunctionDeclarationNode
   */
  protected $builder;

  /**
   * Constructs a FormConverter object.
   */
  public function __construct(TargetInterface $target, FunctionDeclarationNode $builder) {
    $this->target = $target;
    $this->builder = $builder;
  }

  /**
   * {@inheritdoc}
   */
  public function generate() {
    $render = [
      '#theme' => 'dmu_form',
      '#module' => $this->target->id(),
      '#form_id' => $this->builder->getName()->__toString(),
    ];
    $render['#class'] = $this->toTitleCase($render['#form_id']);

    if ($this->builder->has(Filter::isFunctionCall('system_settings_form'))) {
      $render['#config'] = TRUE;
      $this->builder
        ->find(Filter::isFunctionCall('system_settings_form'))
        ->each(function(FunctionCallNode $call) {
          $call->setName('parent::buildForm')->appendArgument(Token::variable('$form_state'));
        });
    }

    $controller = Parser::parseSource(render($render))
      ->find(Filter::isInstanceOf('\Pharborist\ClassNode'))
      ->get(0);

    $this->addMethod($this->builder, $controller, 'buildForm');

    $validator = $this->getHandler('validate');
    if ($validator) {
      $this->addMethod($validator, $controller);
    }

    $submitter = $this->getHandler('submit');
    if ($submitter) {
      $this->addMethod($submitter, $controller);
    }

    return $controller;
  }

  /**
   * Tries to retrieve the form's default validate or submit handler.
   *
   * @param string $type
   *  The handler type (validate or submit).
   *
   * @return null|FunctionDeclarationNode
   */
  protected function getHandler($type) {
    $indexer = $this->target->getIndexer();
    $handler = $this->builder->getName() . '_' . $type;

    if ($indexer->has('function', $handler)) {
      /** @var \Pharborist\Functions\FunctionDeclarationNode $function */
      $function = $indexer->get('function', $handler)->get(0);
      // The first argument ($form) is always passed by reference.
      $function->getParameterAtIndex(0)->setReference(TRUE);

      switch ($type) {
        case 'validate':
          $function->setName('validateForm');
          break;
        case 'submit':
          $function->setName($this->builder->has(Filter::isFunctionCall('system_settings_form')) ? '_submitForm' : 'submitForm');
          break;
        default:
          break;
      }

      return $function;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function addMethod(FunctionDeclarationNode $function, ClassNode $class, $alias = NULL) {
    // Detach the function from its syntax tree so it can be moved into the
    // destination class. If we fail to do this, things can go very wrong.
    // @see \Drupal\drupalmoduleupgrader\Converter\Routing\RouteConverterBase::transferCallback().
    $function->remove();

    // Add the parameters required for FormInterface conformance.
    $parameters = $function->getParameters();
    if (empty($parameters)) {
      $parameters[0] = ParameterNode::create('$form');
      $function->appendParameter($parameters[0]);
    }
    if (sizeof($parameters) == 1) {
      $parameters[1] = ParameterNode::create('$form_state');
      $function->appendParameter($parameters[1]);
    }

    // Provide type hints, again in conformance with FormInterface.
    $parameters[0]->setTypeHint('array');
    $parameters[1]->setTypeHint('FormStateInterface');

    // Additional parameters MUST have a default value of NULL in order to conform
    // to FormInterface.
    for ($i = 2; $i < sizeof($parameters); $i++) {
      $parameters[$i]->setValue(new TokenNode(T_STRING, 'NULL'));
    }

    // @todo Get $form_state's local name and use it for the conversions below.

    // @todo
    // Rewrite calls to form_set_error(), form_clear_error(), form_get_errors(),
    // form_get_error(), form_error(), and form_set_value() to their $form_state
    // method counterparts. That's the annoying thing about devising a stable
    // API: the next step is busywork. None of these rewrites can be done by
    // the Grep plugin because the name of the $form_state variable may vary.
    // @see https://www.drupal.org/node/2121003

    // Convert form_set_error() to $form_state->setErrorByName()
    //  D7: form_set_error($name = NULL, $message = '', $limit_validation_errors = NULL)
    //  D8: setErrorByName($name, $message = '')

    // Convert form_clear_error() to $form_state->clearErrors()
    //  D7: form_clear_error()
    //  D8: clearErrors()

    // Convert form_get_errors() to $form_state->getErrors()
    //  D7: form_get_errors()
    //  D8: getErrors()

    // Convert form_get_error() to $form_state->getError()
    //  D7: form_get_error($element)
    //  D8: getError($element)

    // Convert form_set_value() to $form_state->setValueForElement()
    // NOTE: This one is *really* tricky because $key is basically $element['#parents']
    //  D7: form_set_value($element, $value, &$form_state)
    //  D8: setValue($key, $value)

    $method = $function->cloneAsMethodOf($class);
    if ($alias) {
      $method->setName($alias);
    }
  }

}
