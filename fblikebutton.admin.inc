<?php
// $Id$

/**
 * @file
 * Admin functions for fblikebutton.
 */

/**
 * Configure which node types can be "liked" by users.
 */
function fblikebutton_admin_settings() {
  $fblikebutton_node_options = node_get_types('names');
  $form['fblikebutton_node_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Display the Like button on these content types'),
    '#options' => $fblikebutton_node_options,
    '#default_value' => variable_get('fblikebutton_node_types', array('page')),
    '#description' => t('Each of these content types will have the "like" button automatically added to them.'),
  );/**
  $form['fblikebutton_show_faces'] = array(
    '#type' => 'radios',
    '#title' => t('Display faces in the Like box'),
    '#options' => array('true' => t('Show faces'), 'false' => t('Do not show faces'))
    '#default_value' => variable_get('fblikebutton_show_faces', array('true')),
    '#description' => t('Should users see the faces of other people who have "liked" the same content?'),
  ); */
  return system_settings_form($form);
}
