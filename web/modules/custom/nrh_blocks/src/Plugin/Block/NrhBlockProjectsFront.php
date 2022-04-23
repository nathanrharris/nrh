<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Projects Front' Block.
 *
 * @Block(
 *   id = "nrh_projects_front",
 *   admin_label = @Translation("NRH Projects Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockProjectsFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Projects Front'),
    ];
  }

}
