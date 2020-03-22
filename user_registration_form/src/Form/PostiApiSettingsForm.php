<?php

namespace Drupal\user_registration_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines settings for Posti api settings form.
 */
class PostiApiSettingsForm extends ConfigFormBase {

  /**
   * Configuration name.
   */
  const CONFIG = 'posti_api.settings';

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [static::CONFIG];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'posti_api_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIG);
    $form['#tree'] = TRUE;

    $form['posti'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Posti API settings'),
    ];

    $form['posti']['service_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Service URL'),
      '#default_value' => $config->get('posti.service_url'),
    ];

    $form['posti']['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#default_value' => $config->get('posti.api_key'),
    ];

    $form['posti']['limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Result limit'),
      '#min' => 0,
      '#max' => 20,
      '#default_value' => $config->get('posti.limit'),
    ];

    $form['posti']['municipality'] = [
      '#type' => 'number',
      '#title' => $this->t('Municipalities limit in field'),
      '#min' => 1,
      '#max' => 60,
      '#default_value' => $config->get('posti.municipality'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(static::CONFIG)
      ->set('posti', $form_state->getValue('posti'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
