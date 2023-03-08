<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Video Front' Block.
 *
 * @Block(
 *   id = "nrh_video_front",
 *   admin_label = @Translation("NRH Video Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockVideoFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Video Front'),
    ];
  }

}
