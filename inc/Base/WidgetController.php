<?php
/**
 * @package Deegloo
 */

 namespace Inc\Base;

 use Inc\Base\BaseController;
 use Inc\Api\Widgets\LoginWidget;
 
 class WidgetController extends BaseController
 {
    public function register() {
      $login_widget = new LoginWidget();
      $login_widget->register();
    }
 }
