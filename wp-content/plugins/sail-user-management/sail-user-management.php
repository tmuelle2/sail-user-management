/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

function register_post_types() {
    register_post_types('user-register', ['public' => true])
}

/**
 * Runs on plugin activation.
 */
function plugin_activate() {
    register_post_types();
    flush_rewrite_rules();
}

/**
 * Runs on plugin deactivate.
 */
function plugin_deactivate() {
    unregister_post_type('user-register');
    flush_rewrite_rules();
}

/**
 * Adds the html form required to capture all user account info.
 */
 function user_reg_shortcode($atts = [], $content = null, $tag = '' ) {
 	$o = '<h1>hello shortcode</h1>';
 	return $o;
}

/**
 * Central location to create all shortcodes. Runs on init action.
 */
function shortcodes_init() {
    add_shortcode( 'userregistration', 'user_reg_shortcode' );
} 

register_activation_hook(__FILE__, 'plugin_activate');
register_deactivation_hook(__FILE__, 'plugin_deactivate');
add_action( 'init', 'shortcodes_init' );