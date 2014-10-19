<?php

$plugin = array(
  'label' => t('Bellbottoms'),
  'view_enabled callback' => 'pants_type_bellbottoms_view_enabled',
  'view_disabled callback' => 'pants_type_bellbottoms_view_disabled',
);

function pants_type_bellbottoms_view_enabled() {
  return array(
    '#theme' => 'image',
    '#path' => 'http://ecx.images-amazon.com/images/I/41xXmNdZn8L._SY200_.jpg',
  );
}

function pants_type_bellbottoms_view_disabled() {
  return array(
    '#markup' => t('Off'),
  );
}
