<?php

namespace Drupal\fblikebutton\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a Facebook Like Button Block
 *
 * @Block(
 *   id = "fblikebutton_block",
 *   admin_label = @Translation("Facebook Like Button"),
 * )
 */

class FblikebuttonBlock extends BlockBase {

  /**
  * {@inheritdoc}
  */
  public function build() {

    $values = array(
      '#theme' => 'fblikebutton',
      '#layout' => $this->configuration['layout'],
      '#show_faces' => $this->configuration['show_faces'],
      '#action' => $this->configuration['action'],
      '#font' => $this->configuration['font'],
      '#color_scheme' => $this->configuration['color_scheme'],
      '#width' => $this->configuration['iframe_width'],
      '#height' => $this->configuration['iframe_height'],
      '#other_css' => $this->configuration['iframe_css'],
      '#language' => $this->configuration['language'],
    );

    // If it's not for determined content types
    if($this->configuration['block_url'] != '<current>') {
      $values['#url'] = $this->configuration['block_url'];
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    return $account->hasPermission('access content');
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    global $base_url;
    return array(
      'block_url' => $base_url,
      'layout' => 'standard',
      'show_faces' => TRUE,
      'action' => 'like',
      'font' => 'arial',
      'color_scheme' => 'light',
      'iframe_width' => 450,
      'iframe_height' => 40,
      'iframe_css' => NULL,
      'language' => 'en_US',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state ) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Button settings'),
      '#open' => TRUE,
    );
    $form['settings']['block_url'] = array(
      '#type' => 'textfield',
      '#default_value' => $config['block_url'],
      '#description' => $this->t('URL of the page to like (could be your homepage or a facebook page e.g.)<br> You can also specify &lt;current&gt; to establish the button in nodes'),
    );
    $form['appearance'] = array(
      '#type' => 'details',
      '#title' => $this->t('Button appearance'),
      '#open' => FALSE,
    );
    $form['appearance']['layout'] = array(
      '#type' => 'select',
      '#title' => $this->t('Layout style'),
      '#options' => array('standard' => $this->t('Standard'),
                          'box_count' => $this->t('Box Count'),
                          'button_count' => $this->t('Button Count')),
      '#default_value' => $config['layout'],
      '#description' => $this->t('Determines the size and amount of social context next to the button'),
    );
    // The actial values passed in from the options will be converted to a boolean
    // in the validation function, so it doesn't really matter what we use.
    $form['appearance']['show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Display faces in the box'),
      '#options' => array(TRUE => $this->t('Show faces'), FALSE => $this->t('Do not show faces')),
      '#default_value' => $config['show_faces'],
      '#description' => $this->t('Show profile pictures below the button. Only works with Standard layout'),
    );
    $form['appearance']['action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config['action'],
      '#description' => $this->t('The verb to display in the button.'),
    );
    $form['appearance']['font'] = array(
      '#type' => 'select',
      '#title' => $this->t('Font'),
      '#options' => array(
        'arial' => 'Arial',
        'lucida+grande' => 'Lucida Grande',
        'segoe+ui' => 'Segoe UI',
        'tahoma' => 'Tahoma',
        'trebuchet+ms' => 'Trebuchet MS',
        'verdana' => 'Verdana'
      ),
      '#default_value' => $config['font'],
      '#description' => $this->t('The font to display in the button'),
    );
    $form['appearance']['color_scheme'] = array(
      '#type' => 'select',
      '#title' => $this->t('Color scheme'),
      '#options' => array('light' => $this->t('Light'), 'dark' => $this->t('Dark')),
      '#default_value' => $config['color_scheme'],
      '#description' => $this->t('The color scheme of box environtment'),
    );
    $form['appearance']['iframe_width'] = array(
      '#type' => 'number',
      '#title' => $this->t('Max-width of the iframe (px)'),
      '#default_value' => $config['iframe_width'],
      '#description' => $this->t('Max-width of the iframe, in pixels. Default is 450.'),
    );
    $form['appearance']['iframe_height'] = array(
      '#type' => 'number',
      '#title' => $this->t('Height of the iframe (px)'),
      '#default_value' => $config['iframe_height'],
      '#description' => $this->t('Height of the iframe, in pixels. Default is 80. <em>Note: lower values may crop the output.</em>'),
    );
    $form['appearance']['iframe_css'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Extra css styling needed'),
      '#default_value' => $config['iframe_css'],
      '#description' => $this->t('Extra css attributes needed to make the iframe behave for your specific requirements. Will not necessarily overwrite existing styling. To alter the dimensions of the iframe, use the height and width fields found above.<br/>Example: <em>float: right; padding: 5px;</em>'),
    );
    $form['appearance']['language'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Language'),
      '#default_value' => $config['language'],
      '#description' => $this->t('Specific language to use. Default is English. Examples:<br />French (France): <em>fr_FR</em><br />French (Canada): <em>fr_CA</em><br />More information can be found at http://developers.facebook.com/docs/internationalization/ and a full XML list can be found at http://www.facebook.com/translations/FacebookLocales.xml'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    if (null !== $form_state->getValue('iframe_width')) {
      if ((!is_numeric($form_state->getValue('iframe_width'))) || ($form_state->getValue('iframe_width') <= 0)) {
        $form_state->setErrorByName('iframe_width', $this->t('The width of the like button must be a positive number that is greater than 0 (examples: 201 or 450 or 1024).'));
      }
    }
    if (null !== $form_state->getValue('iframe_height')) {
      if ((!is_numeric($form_state->getValue('iframe_height'))) || ($form_state->getValue('iframe_height') <= 0)) {
        $form_state->setErrorByName('iframe_height', $this->t('The height of the like button must be a positive number that is greater than 0 (examples: 201 or 450 or 1024).'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $block_url = $values['settings']['block_url'];
    $layout = $values['appearance']['layout'];
    $show_faces = $values['appearance']['show_faces'];
    $action = $values['appearance']['action'];
    $font = $values['appearance']['font'];
    $color_scheme = $values['appearance']['color_scheme'];
    $iframe_width = $values['appearance']['iframe_width'];
    $iframe_height = $values['appearance']['iframe_height'];
    $iframe_css = $values['appearance']['iframe_css'];
    $language = $values['appearance']['language'];

    $this->configuration['block_url'] = $block_url;
    $this->configuration['layout'] = $layout;
    $this->configuration['show_faces'] = $show_faces;
    $this->configuration['block_url'] = $block_url;
    $this->configuration['action'] = $action;
    $this->configuration['font'] = $font;
    $this->configuration['color_scheme'] = $color_scheme;
    $this->configuration['iframe_width'] = $iframe_width;
    $this->configuration['iframe_height'] = $iframe_height;
    $this->configuration['iframe_css'] = $iframe_css;
    $this->configuration['language'] = $language;

    // Clear render cache
    $this->clearCache();
  }

  /**
   * Clear render cache to make the button appear or disappear
   */
  protected function clearCache() {
    \Drupal::cache('render')->deleteAll();
  }

}
