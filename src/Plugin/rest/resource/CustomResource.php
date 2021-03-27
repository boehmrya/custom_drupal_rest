<?php

namespace Drupal\custom_api\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "custom_stories_resource",
 *   label = @Translation("Custom Stories Resource"),
 *   uri_paths = {
 *     "canonical" = "/entity/stories/{state_id}"
 *   }
 * )
 */
class CustomResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($state_id = 'US') {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!(\Drupal::currentUser())->hasPermission('access content')) {
       throw new AccessDeniedHttpException();
     }

    $content_types = array(
      'article',
      'external_links'
    );
    $nids = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', $content_types, 'IN')
      ->condition('field_states.entity:taxonomy_term.field_state_id', $state_id)
      ->sort('changed', 'DESC')
      ->range(0, 5)
      ->execute();

    if ($nids) {
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        // date field
        $time = $node->getCreatedTime();
        $date = \Drupal::service('date.formatter')->format($time, 'press_release_date');

        //link
        $node_type = $node->getType();
        $node_url_string = '';
        if ($node_type == 'external_links') {
          $node_url_string = $node->get('field_link')->getValue()[0]['uri'];
        }
        else {
          $node_url = $node->toUrl()->toString(TRUE);
          $node_url_string = $node_url->getGeneratedUrl();
        }
        $data[] = [
          'title' => $node->getTitle(),
          'date' => $date,
          'url' => $node_url_string
        ];
      }
    }
    else {
      $data = ['message' => 'No new nodes.'];
    }
    $response = new ResourceResponse($data);
    // In order to generate fresh result every time (without clearing
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($data);
    return $response;
  }

}
