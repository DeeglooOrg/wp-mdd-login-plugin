<?php
/**
 * @package Deegloo
 */
namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Base\UserManager;
use \Inc\Base\URLRegistry;
use \Firebase\JWT\JWT;
use \Firebase\JWT\SignatureInvalidException;
use \Reference\WP_User;
use \WP_Error;

class RequestHandler extends BaseController
{
  public function register() {
    add_action('parse_request', array($this, 'mdd_login_url_handler'));
  }

  function loadUnauthorizedAccess() {
    require_once("$this->plugin_path/templates/unauthorized_access.php");
  }

  function loadInvalidCredentials() {
    require_once("$this->plugin_path/templates/invalid_credentials.php");
  }

  function loadSsoLogin() {
    require_once("$this->plugin_path/templates/sso_login.php");
  }

  function loadTokenProcessing() {
    require_once("$this->plugin_path/templates/token_processing.php");
  }

  function invalidSecret() {
    echo 'Invalid secret';
  }

  function mockExit() {
    exit();
  }

  function mdd_login_url_handler() {
    if (strpos($_SERVER["REQUEST_URI"], URLRegistry::UNAUTHORIZED_ACCESS_LABEL)) {
      $this->loadUnauthorizedAccess();
      return $this->mockExit();
    } else if (strpos($_SERVER["REQUEST_URI"], URLRegistry::UNAUTHENTICATED_ACCESS_LABEL)) {
      $this->loadInvalidCredentials();
      return $this->mockExit();
    } else if (strpos($_SERVER["REQUEST_URI"], URLRegistry::SSO_LOGIN_LABEL)) {
      $this->loadSsoLogin();
      return $this->mockExit();
    } else if (strpos($_SERVER["REQUEST_URI"], URLRegistry::PARSE_TOKEN_LABEL)) {
      parse_str($_SERVER['QUERY_STRING'], $query);
      
      if (isset($query['state']) && $query['state'] == 'config_test') {
        $this->loadTokenProcessing();
        return $this->mockExit();
      }
      
      try {
        $decrypted_user = JWT::decode($query['access_token'], esc_attr(get_option('client_secret')), array('HS256'));
      } catch (SignatureInvalidException $e) {
        $this->invalidSecret();
        return $this->mockExit();
      }
      
      $permissions_field = esc_attr(get_option('authorities_field'));
      $username_field = esc_attr(get_option('username_field'));
      $email_field = esc_attr(get_option('email_field'));
      $must_have_role = esc_attr(get_option('must_have_role'));
      $username = $decrypted_user->$username_field;
      $email_address = $decrypted_user->$email_field;
      $password = serialize(bin2hex(random_bytes(16)));
      $roles = $this->getAllMappedRolesFromAuthorities($decrypted_user->$permissions_field);
      
      if (!in_array($must_have_role, $decrypted_user->$permissions_field)) {
        wp_redirect( URLRegistry::getUnauthorizedAccessUrl() . '/?provider_uri=' . $query['provider_link'] . '&access_token=' . $query['access_token']);
        return $this->mockExit();
      }

      $userManager = $this->getUserManager();
      if ( !$userManager->userExists( $email_address ) ) {
        $userManager->createUser($username, $password, $email_address, $roles);
      } else {
        $userManager->updateRolesForUserWithEmail($email_address, $roles);
      }

      $userManager->loginAsUser($email_address);
      return $this->mockExit();
    }
 }

  private function getAllMappedRolesFromAuthorities($authorities) {
    $roles = array();
    foreach (wp_roles()->roles as $key => $value) {
      $mapped_role_value = esc_attr(get_option('mdd_role_' . $key));
      if (empty($mapped_role_value) == null) {
        if (in_array($mapped_role_value, $authorities)) {
          $roles = array_merge($roles, array($key));
        }
      }
    }
    
    return $roles;
  }

  function getUserManager() {
    return new UserManager();
  }
}
