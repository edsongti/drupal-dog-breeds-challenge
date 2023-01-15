<?php

namespace Drupal\dog_breed_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Dog Breed Configuration Form.
 */
class DogBreedConfigurationForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'dog_breed_block.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dog_breed_block_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['dog_breed_slug'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dog Breed Slug'),
      '#default_value' => $config->get('dog_breed_slug'),
      '#description' => $this->t("Add one dog slug. Separate compound nouns with dash. Examples: spitz-japanese "),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(static::SETTINGS)
      ->set('dog_breed_slug', $form_state->getValue('dog_breed_slug'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
