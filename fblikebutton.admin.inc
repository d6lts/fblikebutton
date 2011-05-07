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
  );
  $form['fblikebutton_iframe_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Set the iframe height (px)'),
    '#default_value' => variable_get('fblikebutton_iframe_height', '80'),
    '#description' => t('Height of the iframe, in pixels. Default is 80. <em>Note: lower values may crop the output.</em>'),
  );
  $form['fblikebutton_iframe_width'] = array(
    '#type' => 'textfield',
    '#title' => t('Set the iframe width (px)'),
    '#default_value' => variable_get('fblikebutton_iframe_width', '450'),
    '#description' => t('Width of the iframe, in pixels. Default is 450. <em>Note: lower values may crop the output.</em>'),
  );
  $form['fblikebutton_iframe_css'] = array(
    '#type' => 'textfield',
    '#title' => t('Extra css styling needed'),
    '#default_value' => variable_get('fblikebutton_iframe_css', ''),
    '#description' => t('Extra css attributes needed to make the iframe behave for your specific requirements. Will not necessarily overwrite existing styling. To alter the dimensions of the iframe, use the height and width fields found above.<br/>Example: <em>float: right; padding: 5px;</em>'),
  );
  $form['fblikebutton_layout'] = array(
    '#type' => 'select',
    '#title' => t('Layout style'),
    '#options' => array('standard' => t('Standard'), 'box_count' => t('Box Count'), 'button_count' => t('Button Count')),
    '#default_value' => variable_get('fblikebutton_layout', 'standard'),
    '#description' => t('Determines the size and amount of social context next to the button.'),
  );
  $form['fblikebutton_show_faces'] = array(
    '#type' => 'select',
    '#title' => t('Display faces in the box'),
    '#options' => array('show' => t('Show faces'), 'hide' => t('Do not show faces')),
    '#default_value' => variable_get('fblikebutton_show_faces', 'show'),
    '#description' => t('Show profile pictures below the button. Only works if <em>Layout style</em> found above is set to <em>Standard</em> (otherwise, value is ignored).'),
  );
  $form['fblikebutton_action'] = array(
    '#type' => 'select',
    '#title' => t('Verb to display'),
    '#options' => array('like' => t('Like'), 'recommend' => t('Recommend')),
    '#default_value' => variable_get('fblikebutton_action', 'like'),
    '#description' => t('The verbiage to display inside the button itself.'),
  );
  $form['fblikebutton_font'] = array(
	'#type' => 'select',
	'#title' => t('Font'),
	'#options' => array('arial' => 'Arial',
	              'lucida+grande' => 'Lucida Grande',
	              'segoe+ui' => 'Segoe UI',
	              'tahoma' => 'Tahoma',
	              'trebuchet+ms' => 'Trebuchet MS',
	              'verdana' => 'Verdana'),
	'#default_value' => variable_get('fblikebutton_font', 'arial'),
	'#description' => t('The font with which to display the text of the button.'),
  );
  $form['fblikebutton_color_scheme'] = array(
	'#type' => 'select',
	'#title' => t('Color scheme'),
	'#options' => array('light' => t('Light'), 'dark' => t('Dark')),
	'#default_value' => variable_get('fblikebutton_color_scheme', 'light'),
	'#description' => t('The color scheme of the box environtment.'),
  );
  return system_settings_form($form);
}
