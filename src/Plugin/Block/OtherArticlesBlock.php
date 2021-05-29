<?php

namespace Drupal\custom_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "other_articles_block",
 *   admin_label = @Translation("Other Articles block"),
 *   category = @Translation("Custom"),
 * )
 */
class OtherArticlesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = exclude_current_article_block_content();

    $build['result'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#items' => $output,
    ];

    return $build;
  }

}
