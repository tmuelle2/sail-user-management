<?php
/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

$HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
include($HOME_DIR . 'constants.php');

/**
 * Adds the html form required to capture all user account info.
 * If the user is already logged in redirect to profile page.
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
 * Returns the html to display the current users profile info if the user is logged in
 * otherwise redirects to the login page.
 */
function user_profile_shortcode($atts = [], $content = null, $tag = '' ) {
  if (is_user_logged_in()) {
    $sail_user = get_sail_user();
    global $PAGES_DIR;

    $html = get_sail_page($PAGES_DIR . 'profile.html');

    $html = str_ireplace("{{firstName}}", esc_html($sail_user->firstName), $html);
    $html = str_ireplace("{{lastName}}", esc_html($sail_user->lastName), $html);
    $html = str_ireplace("{{email}}", esc_html($sail_user->email), $html);
    $html = str_ireplace("{{phoneNumber}}", esc_html($sail_user->phoneNumber), $html);
    $html = str_ireplace("{{addrLine1}}", esc_html($sail_user->addrLine1), $html);
    $html = str_ireplace("{{addrLine2}}", esc_html($sail_user->addrLine2), $html);
    $html = str_ireplace("{{city}}", esc_html($sail_user->city), $html);
    $html = str_ireplace("{{state}}", esc_html($sail_user->state), $html);
    $html = str_ireplace("{{zipCode}}", esc_html($sail_user->zipCode), $html);
    $html = str_ireplace("{{profilePicture}}", esc_html($sail_user->profilePicture), $html);

    return $html;
  } else {
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/login');
    exit;
  }
} 

/**
 * Returns html to update the user's profile information for the logged in user 
 * otherwise redirects to login page.
 */
function user_update_profile_shortcode($atts = [], $content = null, $tag = '' ) {
  if (is_user_logged_in()) {
    global $PAGES_DIR;
    global $USER_DB_FIELDS;

    $sail_user = get_sail_user();
    $html = parse_html(get_sail_page($PAGES_DIR . 'update-profile.html'));
    populate_inputs($html, $USER_DB_FIELDS, $sail_user);
    return $html->saveHTML();
  } else {
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/login');
    exit;
  }    
}

function user_join_port_shortcode($atts = [], $content = null, $tag = '' ) {
  if (is_user_logged_in()) {
    global $PAGES_DIR;
    return get_sail_page($PAGES_DIR . 'join-port.html');
  } else {
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/register');
    exit;
  } 
}

// Returns the SAIL DB user row for the currently logged in user
function get_sail_user() {
  global $wpdb;
  $user = wp_get_current_user();
  $query = "SELECT * FROM `sail_users` WHERE userId = ";
  $query .= $user->ID;
  return $wpdb->get_row($query);
}

function get_port_member() {
    global $wpdb;
    $user = wp_get_current_user();
    $query = "SELECT * FROM `port_members` WHERE userId = ";
    $query .= $user->ID;
    return $wpdb->get_row($query);
}

/**
 * Attempts to update an html form's inputs with the values of a database object using
 * the field names and input tag names to populate fields.
 */
function populate_inputs($dom_doc, $db_fields, $db_obj) {
  // Get input elements
  $input_list = $dom_doc ->getElementsByTagName("input");

  // Build name to node associative array
  $inputs = array();
  foreach($input_list as $input) {
    $attr = $input->attributes;
    if (attributes_contains($attr, 'name')) {
      $inputs[attributes_get_value($attr, 'name')] = $input;
    }
  }

  // Populate inputs
  foreach($db_fields as $element => $format) {
    if (isset($inputs[$element]) && isset($db_obj[$element])) {
      $inputs[$element]->setAttribute('value', $db_obj[$element]);
    }
  }
}

// Returns the value of a DOMAttr item in an array with a given name, null otherwise
function attributes_get_value($attrs, $name) {
  for ($i = 0; $i < $attrs->length; ++$i) {
    if ($attrs-item($i)->name == $name) {
      return $attrs-item($i)->value;
    }
  }
  return null;
}

// Returns true if a DOMAttr array contains an attribute with a given name 
function attributes_contains($attrs, $name) {
  for ($i = 0; $i < $attrs->length; ++$i) {
    if ($attrs-item($i)->name == $name) {
      return true;
    }
  }
  return false;
}

// Uses PHP's DOMDocument to parse an html string 
function parse_html($str) {
  $doc = new DOMDocument();
  libxml_use_internal_errors(true);
  $doc->loadHTML($str);
  libxml_clear_errors();
  libxml_use_internal_errors(false);
  return $doc;
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
      file_get_contents($path, true)
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
    add_shortcode( 'userJoinPort', 'user_join_port_shortcode');
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
 * 
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

function join_port() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'join-port.php');
}
add_action('admin_post_join_port', 'join_port');
