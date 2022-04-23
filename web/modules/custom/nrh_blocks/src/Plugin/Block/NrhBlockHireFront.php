<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hire Front' Block.
 *
 * @Block(
 *   id = "nrh_hire_front",
 *   admin_label = @Translation("NRH Hire Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockHireFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Hire Front'),
    ];
  }

}
