<?php

namespace Drupal\user_registration_form\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines a 'Municipality' field type.
 *
 * @FieldType(
 *   id = "municipality",
 *   label = @Translation("Municipality"),
 *   description = @Translation("Field rendered municipality and city from posti api."),
 *   default_formatter = "municipality_formatter",
 *   default_widget = "municipality_widget"
 * )
 */
class MunicipalityItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'municipality' => [
          'type' => 'varchar',
          'length' => 84,
        ],
        'city' => [
          'type' => 'varchar',
          'length' => 84,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['municipality'] = DataDefinition::create('string')
      ->setLabel(t('Municipality'));

    $properties['city'] = DataDefinition::create('string')
      ->setLabel(t('City'));

    return $properties;
  }


}
