<?php

declare(strict_types=1);

namespace Drupal\bundle_form_alter\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provide an interface so that Bundle classes can do form alters.
 */
interface BundleFormAlterInterface {

  /**
   * The form alter function for the Bundle classes.
   */
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void;

}
