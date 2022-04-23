<?php

namespace Drupal\nrh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Skills Front' Block.
 *
 * @Block(
 *   id = "nrh_skills_front",
 *   admin_label = @Translation("NRH Skills Front"),
 *   category = @Translation("NRH"),
 * )
 */
class NrhBlockSkillsFront extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Skills Front'),
    ];
  }

}
