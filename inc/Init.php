<?php

namespace Inc;

final class Init
{
  public static function get_services() {
    return [
      Pages\Admin::class,
      Base\Enqueue::class,
      Base\SettingsLinks::class,
      Base\RequestHandler::class,
      Base\WidgetController::class
    ];
  }

  public static function register_services() {
    foreach (self::get_services() as $class) {
      $service = self::instantitate($class);
      if (method_exists($service, 'register')) {
        $service->register();
      }
    }
  }

  private static function instantitate($class) {
    return new $class();
  }
}
