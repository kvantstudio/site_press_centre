<?php

namespace Drupal\site_press_centre\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * SitePressCentre settings form.
 */
class SitePressCentreSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_press_centre_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'site_press_centre.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Загружаем конфигурацию.
    $config = $this->config('site_press_centre.settings');

    // URL адрес корневого каталога.
    $form['url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('URL address of the root directory'),
      '#description' => $this->t('The path must not start with a /.'),
      '#default_value' => $config->get('url') ? $config->get('url') : 'press-centr',
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Записывает значения в конфигурацию.
    $config = \Drupal::service('config.factory')->getEditable('site_press_centre.settings');
    $config->set('url', trim($form_state->getValue('url')));
    $config->save();
  }
}