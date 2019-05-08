<?php

/**
 * Triggr=ers this file on Plugin uninstall
 * 
 * @package Deegloo 
 */

 if (! defined('WP_UNINSTALL_PLUGIN')) {
   die;
 }

 // Access the database via SQL
 global $wpdb;
$wpdb->query( "DELETE FROM wp_mdd_sso_plugin" );
