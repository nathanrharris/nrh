<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Welcome Front' Block.
 *
 * @Block(
 *   id = "nrh_welcome_front",
 *   admin_label = @Translation("NRH Welcome Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockWelcomeFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Welcome Front'),
    ];
  }

}
