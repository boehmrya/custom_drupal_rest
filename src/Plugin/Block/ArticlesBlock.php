<?php

namespace Drupal\custom_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "custom_articles_block",
 *   admin_label = @Translation("Custom Articles block"),
 *   category = @Translation("Custom"),
 * )
 */
class ArticlesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = article_block_get_content();

    $build['result'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#items' => $output,
    ];

    $build['pager'] = [
      '#type' => 'pager',
    ];

    return $build;
  }

}
