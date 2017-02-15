<?php

namespace Drupal\autopost_social\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Form controller for the content_entity_example entity edit forms.
 *
 * @ingroup content_entity_example
 */
class AutoPostSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'autpost_social_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'autopost_social.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('autopost_social.settings');
    $providers = \Drupal::config('autopost_social.settings')->get('providers');
    $form['#tree'] = TRUE;
    foreach ($providers as $key => $provider) {
      $form['provider_' . $key] = [
        '#type'   => 'fieldset',
        '#title'  => $provider['label'],
        '#prefix' => '<div id="names-fieldset-wrapper">',
        '#suffix' => '</div>',
      ];
      $default_values = $config->get('provider_' . $key);
      $form['provider_' . $key]['client_id'] = array(
        '#type'          => 'textfield',
        '#title'         => $this->t('Client Id'),
        '#default_value' => isset($default_values['client_id'])
          ? $default_values['client_id'] : '',
      );

      $form['provider_' . $key]['secret_id'] = array(
        '#type'          => 'textfield',
        '#title'         => $this->t('Secret Id'),
        '#default_value' => isset($default_values['secret_id'])
          ? $default_values['secret_id'] : '',
      );
      $form['provider_' . $key]['page_name'] = array(
        '#type'          => 'textfield',
        '#title'         => $this->t('Machine Page Name'),
        '#default_value' => isset($default_values['page_name'])
          ? $default_values['page_name'] : '',
      );
      $form['provider_' . $key]['access_token'] = array(
        '#type'          => 'textarea',
        '#title'         => $this->t('Access token'),
        '#default_value' => isset($default_values['access_token'])
          ? $default_values['access_token'] : '',
      );
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $providers = \Drupal::config('autopost_social.settings')->get('providers');
    foreach ($providers as $key => $provider) {
      $this->config('autopost_social.settings')
        ->set('provider_' . $key, $form_state->getValue('provider_' . $key))
        ->save();
    }
    parent::submitForm($form, $form_state);
  }
}
