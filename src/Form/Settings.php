<?php

namespace Drupal\custom_events\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Settings.
 *
 * @package Drupal\custom_events\Form
 */
class Settings extends ConfigFormBase {

  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_events_settings';
  }


  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_events.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('custom_events.settings');

    // Get defined text fields from node entities.
    $node_fields = [];
    $fields = $this->entityTypeManager
      ->getStorage('field_storage_config')
      ->loadByProperties(array(
        'entity_type' => 'node',
        'deleted' => FALSE,
        'status' => 1,
      ));

    foreach($fields as $field) {
      if ($field_id = $field->get('field_name')) {
        $node_fields[$field_id] = $field_id;
      }
    }

    $form['source_date_field'] = array(
      '#default_value' => $config->get('source_date_field') ? $config->get('source_date_field') : '',
      '#empty_option' => 'Default',
      '#options' => $node_fields,
      '#required' => FALSE,
      '#title' => 'Source date field',
      '#type' => 'select',
    );

    $form['calendar_date_field'] = array(
      '#default_value' => $config->get('calendar_date_field') ? $config->get('calendar_date_field') : '',
      '#empty_option' => 'Default',
      '#options' => $node_fields,
      '#required' => FALSE,
      '#title' => 'Calendar date field',
      '#type' => 'select',
    );

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      $this->config('custom_events.settings')->set($key, $value);
    }
    $this->config('custom_events.settings')->save();
    parent::submitForm($form, $form_state);
  }

}
