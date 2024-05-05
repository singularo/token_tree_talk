<?php

declare(strict_types=1);

namespace Drupal\page_bundle\Entity;

use Drupal\bundle_form_alter\Form\BundleFormAlterInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Simple class for the page bundle.
 */
class PageBundle extends Node Implements PageBundleInterface, BundleFormAlterInterface {

  /**
   * Return something custom.
   *
   * @return string
   *   The custom thing.
   */
  public function somethingCustom(): string {
    return 'Custom';
  }

  /**
   * {@inheritdoc}
   */
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void {
    $this->addTokenTreeFilter($form);
  }

  private function addTokenTreeFilter(&$form): void {
    // Add token tree.
    $form['field_test']['token_help'] = [
      '#type' => 'container',
      '#weight' => 99,
      'token_link' => [
        '#token_types' => [
          'node%field_foo',
          'node%field_bar',
          'node!field_foo_bar',
        ],
        '#theme' => 'token_tree_link',
        '#show_restricted' => FALSE,
        '#show_nested' => FALSE,
        '#global_types' => FALSE,
        '#click_insert' => TRUE,
        '#recursion_limit' => 1,
        '#weight' => 90,
      ],
    ];
  }

}
