<?php

namespace Drupal\user_registration_form\Plugin\Field\FieldWidget;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user_registration_form\Form\PostiApiSettingsForm;
use Drupal\user_registration_form\PostiApiServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a 'Municipality' field widget.
 *
 * @FieldWidget(
 *   id = "municipality_widget",
 *   label = @Translation("Municipality"),
 *   field_types = {
 *     "municipality"
 *   }
 * )
 */
class MunicipalityWidget extends WidgetBase {

  /**
   * @var \Drupal\user_registration_form\PostiApiServiceInterface
   */
  protected $municipality_service;

  /**
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * MunicipalityWidget constructor.
   *
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param array $settings
   * @param array $third_party_settings
   * @param \Drupal\user_registration_form\PostiApiServiceInterface $municipality_service
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    array $third_party_settings,
    PostiApiServiceInterface $municipality_service,
    ConfigFactoryInterface $configFactory

  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->municipality_service = $municipality_service;
    $this->config = $configFactory->getEditable(PostiApiSettingsForm::CONFIG);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('user_registration_form.municipality_service'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $config = $this->config->get('posti.municipality') ?? NULL;
    $municipalities = $this->municipality_service->get() ?? [];

    if ($config ==! NULL){
      $municipalities = array_slice($municipalities, 0, (int)$config);
    }

    $element = [
      '#type' => 'fieldset',
      '#title' => $this->t('Municipality'),
    ];

    $element['municipality'] = [
      '#type' => 'select',
      '#title' => $this->t('Municipality'),
      '#empty_option' => $this->t('- Select -'),
      '#options' => array_combine($municipalities, $municipalities),
      '#default_value' => $items[$delta]->municipality ?? NULL,
      '#required' => TRUE,
    ];

    $element['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $items[$delta]->city ?? NULL,
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          'select[name="field_municipality['. $delta . '][municipality]"]' => ['!value' => ''],
        ],
      ],
    ];

    return $element;
  }

}
