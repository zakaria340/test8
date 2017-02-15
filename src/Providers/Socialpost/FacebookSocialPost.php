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
   * FacebookSocialPost constructor.
   */
  public function __construct() {
    $config = $this->config('autopost_social.settings');
    $valuesFacebook = $config->get('provider_facebook');
    if (!empty($valuesFacebook)) {
      $this->setClientId($valuesFacebook['client_id']);
      $this->setSecretId($valuesFacebook['secret_id']);
      $this->setPageName($valuesFacebook['page_name']);
      $this->setAccessToken($valuesFacebook['access_token']);
    }
  }

  /**
   * @param \Drupal\node\NodeInterface $node
   */
  public function post(NodeInterface $node) {

    $appId = $this->getClientId();
    $appSecret = $this->getSecretId();
    $pageId = $this->getPageName();

    $userAccessToken = $this->getAccessToken();
    $fb = new \Facebook\Facebook(
      [
        'app_id'                => $appId,
        'app_secret'            => $appSecret,
        'default_graph_version' => 'v2.5',
      ]
    );
    $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken(
      $userAccessToken
    );
    $fb->setDefaultAccessToken($longLivedToken);
    $response = $fb->sendRequest('GET', $pageId, ['fields' => 'access_token'])
      ->getDecodedBody();
    $foreverPageAccessToken = $response['access_token'];

    try {
      $response = $fb->post(
        '/' . $this->getPageName() . '/feed/', array(
          'message' => $node->getTitle(),
        )
      );
    } catch (\Facebook\Exceptions\FacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
  }


  /**
   * @param $clientId
   */
  public function setClientId($clientId) {
    $this->_clientId = $clientId;
  }

  /**
   * @return mixed
   */
  public function getClientId() {
    return $this->_clientId;
  }

  /**
   * @param $pagename
   */
  public function setPageName($pagename) {
    $this->_pagename = $pagename;
  }

  public function getPageName() {
    return $this->_pagename;
  }

  public function setSecretId($secretId) {
    $this->_secret_id = $secretId;
  }

  public function getSecretId() {
    return $this->_secret_id;
  }

  public function setAccessToken($access_token) {
    $this->access_token = $access_token;
  }

  public function getAccessToken() {
    return $this->access_token;
  }
}
