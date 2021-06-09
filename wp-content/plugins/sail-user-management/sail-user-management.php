<?php

/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

/**
 * Adds the html form required to login
 */
function user_signon_shortcode($atts = [], $content = null, $tag = '')
{
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
function user_profile_shortcode($atts = [], $content = null, $tag = '')
{
  $user = wp_get_current_user();

  global $wpdb;
  $query = "SELECT * FROM `sail_users` WHERE userId = ";
  $query .= $user->ID;

  $sail_user = $wpdb->get_row($query);

  $o = '<div>Welcome </div>';
  $o .= esc_html($user->data->user_login);
  $o .= ' aka ';
  $o .= esc_html($sail_user->firstName);
  $o .= '!</div>';
  return $o;
}

/**
 * Runs on init hook, executed for every Wordpress request that is not pre-empted.
 */
function sail_plugin_init()
{
  add_shortcode('userSignOn', 'user_signon_shortcode');
  add_shortcode('userProfile', 'user_profile_shortcode');
}
add_action('init', 'sail_plugin_init');

/**
 * Runs on plugin activation, executed once when the plugin is activated.
 */
function sail_plugin_activation()
{
  // Add route rules, use custom query string parameter to intercept
  add_rewrite_rule( '^register[/]$', 'index.php?sail_user_register=true', 'top' );
}
register_activation_hook( __FILE__, 'sail_plugin_activation' );

/**
 * Allow list customer query string parameters.
 */
function sail_plugin_query_vars( $query_vars )
{
    $query_vars[] = 'sail_user_register';
    return $query_vars;
}
add_action( 'query_vars', 'sail_plugin_query_vars' );

/**
 * Runs on parsing of request query string paramerts.
 */
function sail_plugin_parse_request( &$wp )
{
    if ( array_key_exists( 'sail_user_register', $wp->query_vars ) ) {
        include( dirname( __FILE__ ) . '/forms/registration.php' );
        exit();
    }
}
add_action( 'parse_request', 'sail_plugin_parse_request' );

/************************************************
 * Callback functions that execute in response to 
 * actions, usuallly just calling another file.
 ************************************************/
function sail_user_register()
{
  include_once(dirname( __FILE__ ) . 'user-registration.php');
}
add_action('admin_post_nopriv_sail_user_registration', 'sail_user_register');
add_action('admin_post_sail_user_registration', 'sail_user_register');

function sail_user_signon()
{
  include_once(dirname( __FILE__ ) . 'user-signon.php');
}
add_action('admin_post_nopriv_sail_user_signon', 'sail_user_signon');
add_action('admin_post_sail_user_signon', 'sail_user_signon');
