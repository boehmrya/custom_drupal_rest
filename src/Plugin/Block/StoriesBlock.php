<?php

namespace Drupal\custom_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "custom_stories_block",
 *   admin_label = @Translation("Custom Stories block"),
 *   category = @Translation("Custom"),
 * )
 */
class StoriesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = get_node_content('article', 'story');

    $build['result'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#items' => $output,
    ];

    return $build;
  }

}
