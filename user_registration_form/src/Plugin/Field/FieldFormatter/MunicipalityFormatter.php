<?php

namespace Drupal\user_registration_form\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Defines a 'Municipality' field formatter.
 *
 * @FieldFormatter(
 *   id = "municipality_formatter",
 *   label = @Translation("Municipality"),
 *   field_types = {
 *     "municipality"
 *   }
 * )
 */
class MunicipalityFormatter extends FormatterBase {

  /**
   * @inheritDoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    foreach ($items as $delta => $item) {
      if (!$item->isEmpty()) {
        $element[$delta] = [
          '#markup' => "{$item->city}, {$item->municipality}",
        ];
      }
    }

    return $element;
  }

}
