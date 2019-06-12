<?php
/**
 * @package Deegloo
 */
namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\WidgetCallbacks;
use \Inc\Api\Callbacks\RolesCallbacks;

class Admin extends BaseController
{
  public $settings;
  public $callbacks;
  public $roles;
  public $pages = array();
  public $subpages = array();

  public function register() {
    $this->settings = new SettingsApi();  
    $this->callbacks = new AdminCallbacks();
    $this->widget_callbacks = new WidgetCallbacks();
    $this->roles_callbacks = new RolesCallbacks();
    $this->roles_options = wp_roles();
    
    $this->setPages();
    $this->setSubPages();
    
    $this->setSettings();
    $this->setSections();
    $this->setFields();

    $this->settings->addPages($this->pages)->withSubPage('General')->addSubpages($this->subpages)->register();
  }

  public function setPages() {
    $this->pages = array(
      array(
        'page_title' => 'MDD SSO', 
        'menu_title' => 'MDD SSO', 
        'capability' => 'manage_options', 
        'menu_slug' => 'mdd_sso_plugin', 
        'callback' => array($this->callbacks, 'adminDashboard'),
        'icon_url' => 'dashicons-chart-line', 
        'position' => 110
      )
    );
  }

  public function setSubPages() {
    $this->subpages = array(
      array(
        'parent_slug' => 'mdd_sso_plugin', 
        'page_title' => 'Login Widget', 
        'menu_title' => 'Login Widget', 
        'capability' => 'manage_options', 
        'menu_slug' => 'mdd_login_widget', 
        'callback' => array($this->widget_callbacks, 'mddLoginWidgetConfig'),
      )
    );
  }

  public function setSettings() {
    $args = array(
      array(
        'option_group' => 'mdd_sso_option_group',
        'option_name' => 'client_id'
      ),
      array(
        'option_group' => 'mdd_sso_option_group',
        'option_name' => 'client_secret'
      ),
      array(
        'option_group' => 'mdd_sso_option_group',
        'option_name' => 'authorize_endpoint'
      ),
      array(
        'option_group' => 'mdd_sso_widget_group',
        'option_name' => 'widget_title'
      ),
      array(
        'option_group' => 'mdd_attributes_group',
        'option_name' => 'username_field'
      ),
      array(
        'option_group' => 'mdd_attributes_group',
        'option_name' => 'email_field'
      ),
      array(
        'option_group' => 'mdd_attributes_group',
        'option_name' => 'authorities_field'
      ),
      array(
        'option_group' => 'mdd_attributes_group',
        'option_name' => 'must_have_role'
      )
    );

    foreach ($this->roles_options->roles as $key => $value) {
      $new_role = array(
        array(
          'option_group' => 'mdd_roles_group',
          'option_name' => 'mdd_role_' . $key
        )
      );
      $args = array_merge($args, $new_role);
    }

    $this->settings->setSettings($args);
  }

  public function setSections() {
    $args = array(
      array(
        'id' => 'mdd_sso_admin_index',
        'title' => 'Settings',
        'callback' => array($this->callbacks, 'mddSsoConfigOptions'),
        'page' => 'mdd_sso_plugin'
      ),
      array(
        'id' => 'mdd_sso_widget_index',
        'title' => 'Widget config',
        'callback' => array($this->widget_callbacks, 'mddWidgetConfigOptions'),
        'page' => 'mdd_login_widget'
      ),
      array(
        'id' => 'mdd_role_attributes_mappings',
        'title' => 'Attribute Mapping',
        'callback' => array($this->roles_callbacks, 'mddAttributeConfigOptions'),
        'page' => 'mdd_role_attributes_mapping_section'
      ),
      array(
        'id' => 'mdd_role_mappings',
        'title' => 'Role Mappings',
        'callback' => array($this->roles_callbacks, 'mddRolesConfigOptions'),
        'page' => 'mdd_role_mappings_section'
      ),
    );

    $this->settings->setSections($args);
  }

  public function setFields() {
    $args = array(
      array(
        'id' => 'client_id',
        'title' => 'ClientId',
        'callback' => array($this->callbacks, 'mddSsoClientId'),
        'page' => 'mdd_sso_plugin',
        'section' => 'mdd_sso_admin_index',
        'args' => array(
          'label_for' => 'client_id',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'client_secret',
        'title' => 'Client Secret',
        'callback' => array($this->callbacks, 'mddSsoClientSecret'),
        'page' => 'mdd_sso_plugin',
        'section' => 'mdd_sso_admin_index',
        'args' => array(
          'label_for' => 'client_secret',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'authorize_endpoint',
        'title' => 'Authorize Endpoint',
        'callback' => array($this->callbacks, 'mddSsoAuthorizeEndpoint'),
        'page' => 'mdd_sso_plugin',
        'section' => 'mdd_sso_admin_index',
        'args' => array(
          'label_for' => 'authorize_endpoint',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'widget_title',
        'title' => 'Title',
        'callback' => array($this->widget_callbacks, 'mddSsoWidgetTitle'),
        'page' => 'mdd_login_widget',
        'section' => 'mdd_sso_widget_index',
        'args' => array(
          'label_for' => 'widget_title',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'email_field',
        'title' => 'Email attribute',
        'callback' => array($this->roles_callbacks, 'mddRolesCallback'),
        'page' => 'mdd_role_attributes_mapping_section',
        'section' => 'mdd_role_attributes_mappings',
        'args' => array(
          'label_for' => 'email_field',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'username_field',
        'title' => 'Username attribute',
        'callback' => array($this->roles_callbacks, 'mddRolesCallback'),
        'page' => 'mdd_role_attributes_mapping_section',
        'section' => 'mdd_role_attributes_mappings',
        'args' => array(
          'label_for' => 'username_field',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'authorities_field',
        'title' => 'Authorities attribute',
        'callback' => array($this->roles_callbacks, 'mddRolesCallback'),
        'page' => 'mdd_role_attributes_mapping_section',
        'section' => 'mdd_role_attributes_mappings',
        'args' => array(
          'label_for' => 'authorities_field',
          'class' => 'mdd-sso-input'
        )
      ),
      array(
        'id' => 'must_have_role',
        'title' => 'Required role',
        'callback' => array($this->roles_callbacks, 'mddRolesCallback'),
        'page' => 'mdd_role_attributes_mapping_section',
        'section' => 'mdd_role_attributes_mappings',
        'args' => array(
          'label_for' => 'must_have_role',
          'class' => 'mdd-sso-input'
        )
      )
    );

    foreach ($this->roles_options->roles as $key => $value) {
      $new_role = array(
        array(
          'id' => 'mdd_role_' . $key,
          'title' => $this->roles_options->role_names[$key],
          'callback' => array($this->roles_callbacks, 'mddRolesCallback'),
          'page' => 'mdd_role_mappings_section',
          'section' => 'mdd_role_mappings',
          'args' => array(
            'label_for' => 'mdd_role_' . $key,
            'class' => 'mdd-sso-input'
          )
        )
      );
      $args = array_merge($args, $new_role);
    }

    $this->settings->setFields($args);
  }
}
