<?php
/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

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

add_action( 'init', 'shortcodes_init' );
