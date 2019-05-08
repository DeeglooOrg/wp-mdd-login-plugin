<?php
/**
 * @package Deegloo
 */
namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class RolesCallbacks extends BaseController
{
  public function mddRolesConfigOptions() {
    echo "For each Wordpress role add appropriate role received from JWT token. Roles can only be mapped one to one.";
  }

  public function mddAttributeConfigOptions() {
    echo "For each Wordpress role add appropriate role received from JWT token. Roles can only be mapped one to one.";
  }

  public function mddRolesCallback(array $args) {
    $value = esc_attr(get_option($args['label_for']));
    echo '<input type="text" class="regular-text" name="'. $args['label_for'] .'" value="'.$value.'" placeholder="">';
  }
}
