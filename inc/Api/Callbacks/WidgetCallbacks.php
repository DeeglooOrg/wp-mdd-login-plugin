<?php
/**
 * @package Deegloo
 */
namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class WidgetCallbacks extends BaseController
{
  public function mddLoginWidgetConfig() {
    return require_once("$this->plugin_path/templates/widget_config.php");
  }

  public function mddSsoOptionGroup( $input ) {
    return $input;
  }

  public function mddWidgetConfigOptions() {
    echo "Custom options for sso MDD login.";
  }

  public function mddSsoWidgetTitle() {
    $value = esc_attr(get_option('widget_title'));
    echo '<input type="text" class="regular-text" name="widget_title" value="'.$value.'" placeholder="Title for button">';
  }
}
