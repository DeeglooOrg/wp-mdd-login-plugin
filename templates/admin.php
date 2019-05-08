<h1>MDD SSO <span style="font-size:16px;">v1.0</span></h1>
<div class="wrap">
  <?php settings_errors(); ?>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-1">SSO Configuration</a></li>
    <li><a href="#tab-2">Role Mappings</a></li>
    <li><a href="#tab-3">Updates</a></li>
    <li><a href="#tab-4">About</a></li>
  </ul>

  <div class="tab-content">

    <div id="tab-1" class="tab-pane active">
      <form method="post" action="options.php">
        <?php 
          settings_fields('mdd_sso_option_group');
          do_settings_sections('mdd_sso_plugin');
          $endpoint = esc_attr(get_option('authorize_endpoint'));
          $endpoint .= "?client_id=" . esc_attr(get_option('client_id')) . "&redirect_uri=". \Inc\Base\URLRegistry::getLoginUrl() . "&state=config_test";
        ?>
          <a href="<?php echo $endpoint; ?>" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;" type="button" id="class" class="button button-primary">Test Configuration</a>
        <?php
          submit_button();
        ?>
      </form>  
    </div>
    <div id="tab-2" class="tab-pane">
    <div>
        <a href="<?php echo $endpoint; ?>" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;" type="button" id="class" class="button button-primary">Preview JWT attributes</a>
      </div>
    <form method="post" action="options.php">
        <?php
        settings_fields('mdd_attributes_group');
        do_settings_sections('mdd_role_attributes_mapping_section');
        submit_button();
        ?>
    </form>  
    <form method="post" action="options.php">
        <?php
        settings_fields('mdd_roles_group');
        do_settings_sections('mdd_role_mappings_section');
        submit_button();
      ?>
    </form>  
    </div>
    <div id="tab-3" class="tab-pane">
      <h3>Updates</h3>
    </div>
    <div id="tab-4" class="tab-pane">
      <h3>About</h3>
    </div>

  </div>

  
</div>
  


