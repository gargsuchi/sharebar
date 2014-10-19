<?php

/**
 * @file
 * Provide views data for pants.module.
 *
 * @ingroup views_module_handlers
 */

/**
 * Implements hook_views_data().
 */
function pants_views_data() {
  $data['pants_history']['table']['group'] = t('Pants history');
  $data['pants_history']['table']['base'] = array(
    'field' => 'changed',
    'title' => t('Pants history'),
  );

  // Describe the pants_history.uid field. This was initially copied from
  // $data['node']['uid'] within node_views_data(), and then changed as needed.
  $data['pants_history']['uid'] = array(
    'title' => t('Pants wearer uid'),
    'help' => t('The user whose pants were taken on/off. If you need more fields than the uid, add the Pants wearer relationship.'),
    'relationship' => array(
      'title' => t('Pants wearer'),
      'help' => t('Relate statuses to the user who is/is not wearing pants.'),
      'handler' => 'views_handler_relationship',
      'base' => 'users',
      'field' => 'uid',
      'label' => t('Pants wearer'),
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_user_name',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
    'field' => array(
      'handler' => 'views_handler_field_user',
    ),
  );

  // Describe the pants_history.status field. This was initially copied from
  // $data['node']['status'] within node_views_data(), and then changed as
  // needed.
  $data['pants_history']['status'] = array(
    'title' => t('Pantsed'),
    'help' => t('Whether or not the user is currently wearing pants.'),
    'field' => array(
      'handler' => 'views_handler_field_boolean',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_boolean_operator',
      'label' => t('Pantsed'),
      'type' => 'yes-no',
      // Use status = 1 instead of status <> 0 in WHERE statment.
      'use equal' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  // Describe the pants_history.changed field. This was initially copied from
  // $data['node']['changed'] within node_views_data(), and then changed as
  // needed.
  $data['pants_history']['changed'] = array(
    'title' => t('Changed date'),
    'help' => t('The date the pants were taken on/off.'),
    'field' => array(
      'handler' => 'views_handler_field_date',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );

  // Describe the pants_history.changed_by field. This was initially copied from
  // $data['node']['uid'] within node_views_data(), and then changed as needed.
  $data['pants_history']['changed_by'] = array(
    'title' => t('Pants changer uid'),
    'help' => t('The user whose who did the taking on/off of the pants. If you need more fields than the uid, add the Pants changer relationship.'),
    'relationship' => array(
      'title' => t('Pants changer'),
      'help' => t('Relate statuses to the user who changed it.'),
      'handler' => 'views_handler_relationship',
      'base' => 'users',
      'field' => 'uid',
      'label' => t('Pants changer'),
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_user_name',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
    'field' => array(
      'handler' => 'views_handler_field_user',
    ),
  );

  return $data;
}
