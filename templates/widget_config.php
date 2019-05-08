<h1>MDD SSO <span style="font-size:16px;">beta</span></h1>
<div class="wrap">
  <?php settings_errors(); ?>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-1">Login Widget Configuation</a></li>
  </ul>

  <div class="tab-content">
    <div id="tab-1" class="tab-pane active">
      <form method="post" action="options.php">
        <?php 
          settings_fields('mdd_sso_widget_group');
          do_settings_sections('mdd_login_widget');
          submit_button();
        ?>
      </form>  
      <div>
        Widget example:
      </div>
      <div class="widget-container">
        <?php
        use Inc\Api\Widgets\LoginWidget;
        LoginWidget::printWidgetView(true);
        ?>
      </div>
    </div>
  </div>
</div>
