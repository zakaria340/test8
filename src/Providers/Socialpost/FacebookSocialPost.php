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
    $valuesFacebook = \Drupal::config('autopost_social.settings')->get(
      'provider_facebook'
    );

    if (empty($valuesFacebook) || empty($valuesFacebook['client_id'])
      || $valuesFacebook['secret_id']
    ) {
      throw new Exception("Client id and Secret id are empty");
    }

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

    $pageId = $this->getPageName();
    $fb = new \Facebook\Facebook(
      [
        'app_id'                => $this->getClientId(),
        'app_secret'            => $this->getSecretId(),
        'default_graph_version' => 'v2.5',
        'default_access_token'  => $this->getAccessToken(),
      ]
    );

    try {
      $url = \Drupal\Core\Url::fromRoute(
        'entity.node.canonical', ['node' => $node->id()], ['absolute' => TRUE]
      )->toString();
      $response = $fb->post(
        '/' . $pageId . '/feed/', array(
          'message' => $node->getTitle(),
          //'link' => $url,
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
   * @param null $code
   *
   * @return array|string
   */
  public function generateAccessToken($code = NULL) {
    $fb = new \Facebook\Facebook(
      [
        'app_id'                => $this->getClientId(),
        'app_secret'            => $this->getSecretId(),
        'default_graph_version' => 'v2.5',
      ]
    );

    if (!is_null($code)) {
      $helper = $fb->getRedirectLoginHelper();
      try {
        $accessToken = $helper->getAccessToken();
      } catch (\Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
      $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken(
        $accessToken
      );
      $fb->setDefaultAccessToken($longLivedToken);
      $response = $fb->sendRequest(
        'GET', $this->getPageName(), ['fields' => 'access_token']
      )
        ->getDecodedBody();
      $foreverPageAccessToken = $response['access_token'];

      $facebook_config = \Drupal::config('autopost_social.settings')->get(
        'provider_facebook'
      );
      $facebook_config['access_token'] = $foreverPageAccessToken;

      \Drupal::configFactory()->getEditable('autopost_social.settings')->set(
        'provider_facebook', $facebook_config
      )->save();
      return array('message' => 'Access token generated with success.');
    }
    else {
      $helper = $fb->getRedirectLoginHelper();
      $permissions = ['manage_pages'];
      $urlcallback = \Drupal\Core\Url::fromRoute(
        'autopost_social.access_token', ['provider' => 'facebook'],
        ['absolute' => TRUE]
      )->toString(TRUE)->getGeneratedUrl();
      $loginUrl = $helper->getLoginUrl($urlcallback, $permissions);
      return $loginUrl;
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
