<?php

namespace Drupal\autopost_social\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\autopost_social\SocialPostFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Controller routines for block example routes.
 */
class GenerateAccessTokenController extends ControllerBase {

  /**
   * A simple controller method to explain what the block example is about.
   */
  public function generate($provider = NULL) {
    if (!is_null($provider)) {
      $postInstance = SocialPostFactory::create($provider);
      // We have code we can generate access token.
      if (isset($_GET['code']) && $_GET['code']) {
        $result = $postInstance->generateAccessToken($_GET['code']);
        if (isset($result['error'])) {
          drupal_set_message(
            t('An error occurred when trying to generate access token.'),
            'error'
          );
        }
        else {
          drupal_set_message(
            $result['message'], 'status'
          );
        }
        $configUrl = \Drupal\Core\Url::fromRoute(
          'autopost_social.settings',
          ['absolute' => TRUE]
        )->toString();
        return new RedirectResponse($configUrl);
      }
      else {
        // Get autorization.
        $urllresponse = $postInstance->generateAccessToken();
        return new TrustedRedirectResponse($urllresponse);
      }
    }
    else {
      drupal_set_message(
        t('Param token dont exist'),
        'error'
      );
    }
  }

}
