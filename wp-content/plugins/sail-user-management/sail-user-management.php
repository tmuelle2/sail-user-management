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
 * Adds the html form required to logout
 */
function user_logout_shortcode($atts = [], $content = null, $tag = '' ) {
  global $PAGES_DIR;
  return get_sail_page($PAGES_DIR . 'logout.html');
}

/**
 * Adds the html form required for when a user forgets their password
 */
function user_forgot_password_shortcode($atts = [], $content = null, $tag = '' ) {
  global $PAGES_DIR;
  return get_sail_page($PAGES_DIR . 'forgot-password.html');
}

/**
 * Adds the html form required for when a user needs to change their password
 */
function user_change_password_shortcode($atts = [], $content = null, $tag = '' ) {
  global $PAGES_DIR;
  return get_sail_page($PAGES_DIR . 'change-password.html');
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
    populate_form_elements($html, $USER_DB_FIELDS, $sail_user);
    return $html->saveHTML();
  } else {
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/login');
    exit;
  }    
}

/**
 * Returns the html form to join a port if logged in,
 * otherwise redirects to register page.
 */
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

/**
 * Returns html to update the user's port info for the logged in user, if they do not have port info returns an empty strings
 * otherwise redirects to register page.
 */
function user_update_port_shortcode($atts = [], $content = null, $tag = '' ) {
  if (is_user_logged_in()) {
    global $PAGES_DIR;
    global $PORT_DB_FIELDS;

    $port_member = get_port_member();

    if (isset($port_member, $port_member->userId)) {
      $html = parse_html(get_sail_page($PAGES_DIR . 'update-port.html'));
      populate_form_elements($html, $PORT_DB_FIELDS, $port_member);
      return $html->saveHTML();
    }
    else {
      return "";
    }

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

  // fetch sail_user
  $result = $wpdb->get_row($query);

  // fetch the image blob seperately so it actually works?
  // NOTE: probably don't need this code anymore since we save the url now instead of a blob
  $image = $wpdb->get_var( 
    $wpdb->prepare("SELECT profilePicture FROM `sail_users` WHERE userId = %d", $user->ID)  
  );
  $result->profilePicture = $image;

  return $result;
}

// Returns the port member info of the currently logged in user if it exists
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
function populate_form_elements($dom_doc, $db_fields, $db_obj) {
  $tags = array('input', 'select', 'textarea');
  $element_map = array();
  foreach ($tags as $tag) {
    // Get elements 
    $element_list = $dom_doc->getElementsByTagName($tag);
    // Build tag to name to node associative arrays
    $element_map[$tag] = name_to_node_map($element_list);
  }

  $db_arr = get_object_vars($db_obj); 

  // Populate elements 
  foreach($db_fields as $element => $format) {
    foreach ($tags as $tag) {
      if (isset($element_map[$tag][$element]) && isset($db_arr[$element])) {
        switch ($tag) {
          case 'input':
            populate_input($element_map[$tag][$element], $db_arr[$element]);
            break;
          case 'select':
            populate_select($element_map[$tag][$element], $db_arr[$element]);
            break;
          default:
            populate_element($element_map[$tag][$element], $db_arr[$element]);
            break;
        }  
        continue;
      }
    }
  }
}

// Returns a mapping a list of DOM nodes' name attribute value to the node(s) with that name
function name_to_node_map($nodes) {
  $arr = array();
  foreach($nodes as $node) {
    $node_name = $node->attributes->getNamedItem('name');
    if ($node_name != null) {
      if (isset($arr[$node_name->nodeValue])) {
        array_push($arr[$node_name->nodeValue], $node);
      } else {
        $arr[$node_name->nodeValue] = array($node);
      }
    }
  }
  return $arr;
}

// Populates vanilla (text, data, etc.) and radio input elements with value
function populate_input($dom_input, $value) {
  $count = count($dom_input);
  if ($count > 1 && $dom_input[0]->attributes->getNamedItem('type')->nodeValue == 'radio') {
    for($i = 0; $i < $count; $i++) {
      error_log("Looking for $value in " . print_r($dom_input[$i]->attributes->getNamedItem('value'), true));
      if ($dom_input[$i]->attributes->getNamedItem('value')->nodeValue == $value) {
        $dom_input[$i]->setAttribute('checked', '');
      } else {
        $dom_input[$i]->removeAttribute('checked');
      }
    }
  } elseif ($count == 1) { 
    $dom_input[0]->setAttribute('value', $value);
  } else {
    $attrs = dom_named_node_map_to_string($dom_input[0]->attributes);
    error_log("Unsupported input element(s). There are $count elements, the first element's attributes are: $attrs");
  }
}

// Populates a DOM select element with the correct option selected if it exists
function populate_select($dom_select, $option) {
  if (count($dom_select) > 1) {
    return;
  }
  $children = $dom_select[0]->childNodes;
  for ($i = 0; $i < $children->length; ++$i) {
    if ($children->item($i)->attributes != null 
        && $children->item($i)->attributes->getNamedItem('value') != null 
        && $children->item($i)->attributes->getNamedItem('value')->nodeValue == $option) {
      $children->item($i)->setAttribute('selected', '');
    }
  }
}

function populate_element($dom_element, $value) {
  if (count($dom_element) > 1) {
    return;
  }
  $dom_element[0]->nodeValue = $value;
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

function dom_named_node_map_to_string($map) {
  $count = $map->count();
  $str = "";
  for ($i = 0; $i < $count; ++$i) {
    $str .= print_r($map->item($i), true);
  }
  return $str;
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
    add_shortcode( 'userLogout', 'user_logout_shortcode');
    add_shortcode( 'userForgotPassword', 'user_forgot_password_shortcode');
    add_shortcode( 'userChangePassword', 'user_change_password_shortcode');
    add_shortcode( 'userProfile', 'user_profile_shortcode' );
    add_shortcode( 'userUpdateProfile', 'user_update_profile_shortcode' );
    add_shortcode( 'userJoinPort', 'user_join_port_shortcode');
    add_shortcode( 'userUpdatePort', 'user_update_port_shortcode');
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

function sail_user_logout() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'user-logout.php');
}
add_action('admin_post_sail_user_logout', 'sail_user_logout');

function sail_user_forgot_password() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'user-forgot-password.php');
}
add_action('admin_post_nopriv_sail_user_forgot_password', 'sail_user_forgot_password');

function sail_user_change_password() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'user-change-password.php');
}
add_action('admin_post_nopriv_sail_user_change_password', 'sail_user_change_password');
add_action('admin_post_sail_user_change_password', 'sail_user_change_password');

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

function update_port() {
  global $HOME_DIR;
  include_once($HOME_DIR . 'update-port.php');
}
add_action('admin_post_update_port', 'update_port');


// Makes it so we can access custom query params from urls (like when a user clicks a password reset link)
function add_custom_query_params( $qvars ) {
    $qvars[] = 'pw_reset_key';
    $qvars[] = 'user_email';
    return $qvars;
}
add_filter( 'query_vars', 'add_custom_query_params' );
