<?php
/**
 * @package Deegloo
 */
namespace Inc\Base;

class URLRegistry
{
  const PARSE_TOKEN_LABEL = 'parse-sso-token';
  const SSO_LOGIN_LABEL = 'sso-login';
  const UNAUTHORIZED_ACCESS_LABEL = 'mdd-unauthorized-access';
  const UNAUTHENTICATED_ACCESS_LABEL = 'error_description';


  public static function getLoginUrl() {
    return get_site_url() . '/' . self::SSO_LOGIN_LABEL;
  }

  public static function getUnauthorizedAccessUrl() {
    return get_site_url() . '/' . self::UNAUTHORIZED_ACCESS_LABEL;
  }

  public static function getParseTokenUrl() {
    return get_site_url() . '/' . self::PARSE_TOKEN_LABEL;
  }
}
