<?php
/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */


global $sail_plugin_home_dir = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';

/**
 * Adds the html form required to capture all user account info.
 */
function user_reg_shortcode($atts = [], $content = null, $tag = '' ) {
    
}

/**
 * Adds the html form required to login
 */
 function user_signon_shortcode($atts = [], $content = null, $tag = '' ) {
  $o = '<style>
.required-field:after {
  content: " *";
    color: red;
}
.field-label {
    margin-bottom: 10px;
}
.text-input-field {
  min-height: 26px;
    font-size: 16px;
  width: 100%;  
}
.select-field {
    min-height: 26px;
    font-size: 16px;
    margin: 1.425 0 1.425 0;
}
.flex-container {
    display: flex;
}

.flex-child {
    flex: 1;
}  

.flex-child:first-child {
    margin-right: 20px;
} 
</style>
<form accept-charset="UTF-8" action="';
$o .= esc_url(admin_url('admin-post.php'));
$o .= '" id="user_signon" autocomplete="on" method="post">
    <input type="hidden" name="action" value="sail_user_signon">
    <h5 class="field-label required-field">Email</h5>
    <input name="email" type="email" class="text-input-field" required /> <br /> 
    <h5 class="field-label required-field">Password</h5>
    <input name="password" type="password" class="text-input-field" required /> <br /> 
    <input name="remember" type="checkbox" />
    <input name="remember" value="0" type="hidden">
    <label for="remember"> Remember me</label><br>
</form>
<button type="submit" form="user_signon" value="Submit">Login</button>';
  return $o;
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

/**
 * Central location runs on Wordpress init hook.
 */
function sail_plugin_init() {
    global $wp_rewrite;
    $login_url = plugins_url( 'forms/login.php', __FILE__ );
    $login_url = substr( $login_url, strlen( home_url() ) + 1 );
    // The pattern is prefixed with '^'
    // The substitution is prefixed with the "home root", at least a '/'
    // This is equivalent to appending it to `non_wp_rules`
    $wp_rewrite->add_external_rule( 'login.php$', $login_url );
    //add_shortcode( 'userRegistration', 'user_reg_shortcode' );
    add_shortcode( 'userSignOn', 'user_signon_shortcode' );
    add_shortcode( 'userProfile', 'user_profile_shortcode' );
} 

function sail_user_register() {
    include_once($sail_plugin_home_dir . 'user-registration.php');
}

function sail_user_signon() {
    include_once($sail_plugin_home_dir . 'user-signon.php');
}

add_action('init', 'sail_plugin_init' );
/**
 * admin_post_ and admin_post_nopriv_ actions get triggered by a form post request
 * with the "action" field set to the suffix.  The nopriv one is called if the request 
 * comes from an unauntheticated user and the base is called for autheticated.
 */
add_action('admin_post_nopriv_sail_user_registration', 'sail_user_register');
add_action('admin_post_sail_user_registration', 'sail_user_register');
add_action('admin_post_nopriv_sail_user_signon', 'sail_user_signon');
add_action('admin_post_sail_user_signon', 'sail_user_signon');
