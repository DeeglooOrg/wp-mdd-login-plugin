<?php
/**
 * @package Deegloo
 */
namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
  public function adminDashboard() {
    return require_once("$this->plugin_path/templates/admin.php");
  }

  public function mddSsoOptionGroup( $input ) {
    return $input;
  }

  public function mddSsoConfigOptions() {
    echo "Custom options for sso MDD login.";
  }

  public function mddSsoClientId() {
    $value = esc_attr(get_option('client_id'));
    echo '<input type="text" class="regular-text" name="client_id" value="'.$value.'" placeholder="Your client id here">';
  }

  public function mddSsoClientSecret() {
    $value = esc_attr(get_option('client_secret'));
    echo '<input type="text" class="regular-text" name="client_secret" value="'.$value.'" placeholder="Your client secret here">';
  }

  public function mddSsoAuthorizeEndpoint() {
    $value = esc_attr(get_option('authorize_endpoint'));
    echo '<input type="text" class="regular-text" name="authorize_endpoint" value="'.$value.'" placeholder="Login endpoint">';
  }
}
