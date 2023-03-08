<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Menu Front' Block.
 *
 * @Block(
 *   id = "nrh_menu_front",
 *   admin_label = @Translation("NRH Menu Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockMenuFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Menu Front'),
    ];
  }

}
