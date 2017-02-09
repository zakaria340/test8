<?php

namespace Drupal\autopost_social\Services;

use \Drupal\node\NodeInterface;

use Drupal\autopost_social\SocialPostFactory;

/**
 *
 */
class SocialPostService {

  public function __construct() {
  }


  public function post($providers, NodeInterface $node) {
    foreach($providers as $provider) {
      $postInstance = SocialPostFactory::create($provider);
      $postInstance->post($node);
    }
  }
}
