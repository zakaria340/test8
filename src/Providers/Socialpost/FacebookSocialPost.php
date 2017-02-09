<?php

namespace Drupal\autopost_social\Providers\Socialpost;

use Drupal\autopost_social\SocialPostBase;
use \Drupal\node\NodeInterface;


class FacebookSocialPost extends SocialPostBase {

  protected $_clientId;

  protected $_pagename;

  protected $_secret_id;

  protected $access_token;


  /**
   * @param \Drupal\node\NodeInterface $node
   */
  public function post(NodeInterface $node) {

    $appId = $this->getClientId();
    $appSecret = $this->getSecretId();
    $pageId = 'watchmoviestv';

    $userAccessToken = "EAAEP8bCKjkIBAEGSNZCCrOM9yXAS6KpYjysOn20Xr8euzZC9xdMP2wLIB8J4QDMzgmHY3bl79G8sPtgnr39zdpdyxUOdCZChZAGzlmegK9AnopVePblrpfV2niIQu8mGBUkqHhEHvhSZAdKD9pZBJDgA9ZAk0b2PxUycxcP2srYwjjpR9HS3ZBhRHK4sv7lZA42YZD";

    $fb = new \Facebook\Facebook([
      'app_id' => $appId,
      'app_secret' => $appSecret,
      'default_graph_version' => 'v2.5'
    ]);

    $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($userAccessToken);

    $fb->setDefaultAccessToken($longLivedToken);

    $response = $fb->sendRequest('GET', $pageId, ['fields' => 'access_token'])
      ->getDecodedBody();

    $foreverPageAccessToken = $response['access_token'];
    var_dump($foreverPageAccessToken);die;

    try {
      $response = $fb->post( '/' . $this->getPageName() . '/feed/', array(
        'message' => 'TEST ZAZAZA',
      ));
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
  }


  /**
   * Place an order for a sandwich.
   *
   * This is just an example method on our plugin that we can call to get
   * something back.
   *
   * @param array $extras
   *   An array of extra ingredients to include with this sandwich.
   *
   * @return string
   *   Description of the sandwich that was just ordered.
   */
  public function setClientId($clientId) {
    // TODO: Implement setClientId() method.
  }

  public function getClientId() {
    return "299005700116034";
  }

  public function setPageName($pagename) {
    // TODO: Implement setClientId() method.
  }

  public function getPageName() {
    return "watchmoviestv";
  }

  public function setSecretId($secretId) {
    // TODO: Implement setSecretId() method.
  }

  public function getSecretId() {
    return "a2ee76076962f502e2f889382e2e239d";
  }

  public function setAccessToken($access_token) {
    // TODO: Implement setAccessToken() method.
  }

  public function getAccessToken() {
    return "EAAEP8bCKjkIBABwjNc45Pd3rp50diDfRbMROLBUUix7bPkqdnGYxiMz2jUOEWnk33T3yHKqy4ZAZCZCRHefp7QYLTRf4BycG3o81VlarVakZA9R020in9CZCiQtcAKmZAkwhMwySLuTZALqtUfABMEvSZCeAHngmLW7QGyy6fZAD6252MPFSNzrSCctVpqVETMrsZD";
  }
}
