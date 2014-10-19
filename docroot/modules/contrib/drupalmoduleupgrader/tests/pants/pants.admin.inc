<?php

/**
 * @file
 * Pants administration UI.
 */

/**
 * Form builder; The pants settings form.
 */
function pants_settings() {
  $form['pants_type'] = array(
    '#type' => 'radios',
    '#title' => t('Pants type'),
    '#options' => array(
      '' => t('None (just show on/off status)'),
    ),
    '#default_value' => variable_get('pants_type', ''),
    '#description' => t('Choose pants type to show on the user profile.'),
  );

  ctools_include('plugins');
  foreach(ctools_get_plugins('pants', 'pants_type') as $pants_type_id => $pants_type_info) {
    $form['pants_type']['#options'][$pants_type_id] = $pants_type_info['label'];
  }

  return system_settings_form($form);
}
