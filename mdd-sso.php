<?php
/**
 * @package Deegloo
 */
/*
Plugin Name: MDD SSO Login
Plugin URI: https://deegloo.com/
Description: MDD SSO Login plugin supporting JWT token login.
Version: 1.0.0
Author: Deegloo d.o.o.
Author URI: https://deegloo.com/
*/


// If this file is called as script, abort!
defined( 'ABSPATH') or die('Hey, what are you doing here? This is WP plugin, not script.');

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Inc\Base\Activate;
use Inc\Base\Deactivate;

/**
 * The code that runs during plugin activation
 */
function activate_mdd_sso_plugin() {
  Activate::activate();
}

/**
 * The code that runs during plugin deactivation
 */
function deactivate_mdd_sso_plugin() {
  Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_mdd_sso_plugin');
register_deactivation_hook(__FILE__, 'deactivate_mdd_sso_plugin');

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('Inc\\Init')) {
  Inc\Init::register_services();
}
