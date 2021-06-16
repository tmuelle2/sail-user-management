<?php
/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

$HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
$PAGES_DIR = $HOME_DIR . 'pages/';

/**
 * Adds the html form required to capture all user account info.
 */
 function user_reg_shortcode($atts = [], $content = null, $tag = '' ) {
   global $PAGES_DIR;
   return get_sail_page($PAGES_DIR . 'registration.html');
 } 

/**
 * Adds the html form required to login
 */
function user_signon_shortcode($atts = [], $content = null, $tag = '' ) {
  global $PAGES_DIR;
  return get_sail_page($PAGES_DIR . 'signon.html');
}

/**
 * Adds the html to display the current users profile info
 */
function user_profile_shortcode($atts = [], $content = null, $tag = '' ) {
  $user = wp_get_current_user();

  global $wpdb;
  $query = "SELECT * FROM `sail_users` WHERE userId = ";
  $query .= $user->ID;

  $sail_user = $wpdb->get_row($query);

  $o = '<div>Welcome </div>';
  $o .= esc_html($user->data->user_login);
  $o .= ' aka ';
  $o .= esc_html($sail_user->firstName);
  $o .='!</div>';
  return $o;
} 

function user_update_profile_shortcode($atts = [], $content = null, $tag = '' ) {
  global $PAGES_DIR;
  return get_sail_page($PAGES_DIR . 'update_profile.html');
}
/**
  * Loads an html page as string replacing admin post url if present.
  * Also, injects the ./pages/common.css file as a <style> block.
  */
function get_sail_page($path) {
  global $PAGES_DIR;
  return '<style>' . file_get_contents($PAGES_DIR . 'common.css', true) . '</style>' .
    str_replace(
      '<?php esc_url(admin_url(\'admin-post.php\')); ?>', 
      esc_url(admin_url('admin-post.php')), 
      file_get_contents(path, true)
    );
}

/**
 * Runs on init hook which is called for every web request Wordpress handles.
 */
function sail_plugin_init() {
    add_shortcode( 'userRegistration', 'user_reg_shortcode' );
    add_shortcode( 'userSignOn', 'user_signon_shortcode' );
    add_shortcode( 'userProfile', 'user_profile_shortcode' );
    add_shortcode( 'userUpdateProfile', 'user_update_profile_shortcode' );
} 
add_action('init', 'sail_plugin_init' );

/**
 * Runs on plugin activation, only run once when the plugin activates.
 */
function sail_plugin_activate() {
}
register_activation_hook( __FILE__, 'sail_plugin_activate' );

/***********************************************************************
 * Below are post callbacks. To wire up a new callback
 * include a hidden input with a target value, like:
 *  <input type="hidden" name="action" value="your_target_value">
 * Then implement a function(s) to handle the post and add and action(s)
 * for it.  admin_post_your_target_value will be invoked when an 
 * authenticated user posts and admin_post_nopriv_your_target_value will
 * be invoked when an unautheticated user posts.
 ***********************************************************************/
function sail_user_register() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'user-registration.php');
}
add_action('admin_post_nopriv_sail_user_registration', 'sail_user_register');

function sail_user_signon() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'user-signon.php');
}
add_action('admin_post_nopriv_sail_user_signon', 'sail_user_signon');

function sail_user_update() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'user-update.php');
}
add_action('admin_post_sail_user_update', 'sail_user_update');
