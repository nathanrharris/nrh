<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Section Menu Front' Block.
 *
 * @Block(
 *   id = "nrh_section_menu_front",
 *   admin_label = @Translation("NRH Section Menu Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockSectionMenuFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Section Menu Front'),
    ];
  }

}
