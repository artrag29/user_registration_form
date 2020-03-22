<?php

namespace Drupal\user_registration_form;

/**
 * Defines an interface for posti api service.
 */
interface PostiApiServiceInterface {

  /**
   * Get municipalities.
   *
   * @return array
   */
  public function get();

}
