<?php

$plugin = array(
  'label' => t('MC Hammer'),
  'view_enabled callback' => 'pants_type_mchammer_view_enabled',
  'view_disabled callback' => 'pants_type_mchammer_view_disabled',
);

function pants_type_mchammer_view_enabled() {
  return array(
    '#theme' => 'image',
    '#path' => 'http://nickyscostumes.com.au/images/uploads/hammerpants.jpg',
  );
}

function pants_type_mchammer_view_disabled() {
  return array(
    '#markup' => t('Off'),
  );
}
