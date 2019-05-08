<?php
/**
 * @package Deegloo
 */

 namespace Inc\Base;

 class Activate
 {
   public static function activate() {
    //  Activate::setup_plugin_table();
     flush_rewrite_rules();
   }

    static function setup_plugin_table() {
      global $wpdb;
      $table_name = $wpdb->prefix . "mdd_sso_plugin"; 
      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        JWT_secret text NOT NULL,
        url varchar(55) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
    }
 }
