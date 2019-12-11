<?php
/**
 * @package Deegloo
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{
  public function register() {
    add_action('admin_enqueue_scripts', array( $this, 'enqueue'));
    add_action('wp_enqueue_scripts', array( $this, 'wp_enqueue'));
    add_action('login_enqueue_scripts', array( $this, 'login_enqueue'));
  }

  function enqueue() {
    // enqueue all the scripts
    wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/admin_mystyle.css' );
    wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/admin_myscript.js' );
  }
  
  function wp_enqueue() {
    // enqueue all the scripts
    wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/wp_mystyle.css?v=1.4' );
    wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/add_token.js' );
  }
  function login_enqueue() {
    // enqueue all the scripts
    wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/wp_mystyle.css?v=1.4' );
    wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/login_script.js?v=1.4' );
  }
}
