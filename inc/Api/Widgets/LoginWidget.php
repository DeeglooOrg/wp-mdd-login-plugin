<?php
/**
 * @package Deegloo
 */

 namespace Inc\Api\Widgets;

 use WP_Widget;
 
 class LoginWidget extends WP_Widget
 {
    public $widget_ID;
    public $widget_name;
    public $widget_options = array();
    public $control_options = array();

    public function __construct() {
      $this->widget_ID = 'mdd_sso_login_widget';
      $this->widget_name = 'MDD SSO Login';
      $this->widget_options = array(
        'classname' => $this->widget_ID,
        'description' => $this->widget_name,
        'customize_selective_refresh' => true
      );
      $this->control_options = array(
        'width' => 400,
        'height' => 350
      );
    }

    public function register() {
      parent::__construct($this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options);
      add_action('widgets_init', array($this, 'widgetInit'));
      add_action('login_form', array($this, 'widgetLoginPage'));
      add_action('login_footer', array($this, 'widgetLoginFooter'));
    }

    public function widgetInit() {
      register_widget($this);
    }

    public function widgetLoginPage() {
      if (!LoginWidget::isConfigurationValid()) {
        return;
      }
      echo "<div style='clear:both; text-align:center;'>";
      $this->printWidgetView();

      echo "</div>";
    }

    public function widgetLoginFooter() {
      if (!LoginWidget::isConfigurationValid()) {
        return;
      }
      echo "<p style='text-align:center;' id=\"backtoblog\"><a href='#' style='cursor:pointer' onclick='showAdminFields(this)'> Admin Login</a></p>";
    }

    public function widget($args, $instance) {
      $endpoint = esc_attr(get_option('authorize_endpoint'));
      $endpoint .= "?client_id=" . esc_attr(get_option('client_id')) . "&redirect_uri=". \Inc\Base\URLRegistry::getLoginUrl();
      // echo $args['before_widget'];
      if (!empty($instance['title'])) {
          // echo $args['before_title'];
          echo "<a href='". $endpoint ."' class='mdd-login-widget' type='button'>".apply_filters('widget_title', $instance['title'])."</a>";
          // echo $args['after_title'];
        }
      // echo $args['after_widget'];
    }

    public function form($instance) {
      $title = !empty($instance['title']) ? $instance['title'] : esc_html('Custom text');
      $titleId = esc_attr($this->get_field_id('title'));
      $titleName = esc_attr($this->get_field_name('title'));
      ?>
        <p>
          <label for="<?php echo $titleId; ?>">Title</label>
          <input type="text" class="widefat" id="<?php echo $titleId; ?>" name="<?php echo $titleName; ?>" value="<?php echo esc_attr($title); ?>">
        </p>
      <?php
    }

    public function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = sanitize_text_field($new_instance['title']);

      return $instance;
    }

    public static function printWidgetView($disabled = false) {
      $endpoint = esc_attr(get_option('authorize_endpoint'));
      $endpoint .= "?client_id=" . esc_attr(get_option('client_id')) . "&redirect_uri=". \Inc\Base\URLRegistry::getLoginUrl();
      if ($disabled) {
        $endpoint = '#';
      }
      $title = esc_attr(get_option('widget_title'));
      echo "<a href='". $endpoint ."' class='mdd-login-widget' type='button'>".apply_filters('widget_title', $title)."</a>";
      if (!LoginWidget::isConfigurationValid()) {
        echo "<b>Invalid widget configuration!</b> <br/>Plugin will not be shown on login screen. Please check plugin configuration page!";
      }
    }

    public static function isConfigurationValid() {
      return  !empty(trim(esc_attr(get_option('client_id')))) && 
              !empty(trim(esc_attr(get_option('client_secret')))) &&
              !empty(trim(esc_attr(get_option('authorize_endpoint')))) &&
              !empty(trim(esc_attr(get_option('widget_title')))) &&
              !empty(trim(esc_attr(get_option('username_field')))) &&
              !empty(trim(esc_attr(get_option('email_field')))) &&
              !empty(trim(esc_attr(get_option('authorities_field'))));
    }
 }
