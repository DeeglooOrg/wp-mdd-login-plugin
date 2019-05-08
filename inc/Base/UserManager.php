<?php
/**
 * @package Deegloo
 */
namespace Inc\Base;

class UserManager
{
  public function userExists($email) {
    return email_exists($email);
  }

  public function createUser($username, $password, $email, $roles) {
    $user_id = wp_create_user( $username, $password, $email );
    $user = get_user_by( 'email', $email);
    $isFirst = true;
    foreach ($roles as $role) {
      if ($isFirst) {
        $user->set_role( $role );  
        $isFirst = false;
      } else {
        $user->add_role( $role );
      }
    }
  }

  public function updateRolesForUserWithEmail($email, $roles) {
    $user = get_user_by( 'email', $email);
    if (!$user) {
      return;
    }
    $isFirst = true;
    foreach ($roles as $role) {
      if ($isFirst) {
        $user->set_role( $role );  
        $isFirst = false;
      } else {
        $user->add_role( $role );
      }
    }
  }

  public function loginAsUser($email) {
    if ( email_exists($email) ) {
        $user = get_user_by( 'email', $email);
        wp_clear_auth_cookie();
        wp_set_current_user ( $user->ID );
        wp_set_auth_cookie  ( $user->ID );

        $redirect_to = get_site_url();
        wp_safe_redirect( $redirect_to );
    } else {
      wp_redirect( wp_login_url() );
    }
  }
}
