<?php
/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

function sail_register_post_types() {
    register_post_types('user-register', ['public' => true]);
}

/**
 * Runs on plugin activation.
 */
function sail_plugin_activate() {
    register_post_types();
    flush_rewrite_rules();
}

/**
 * Runs on plugin deactivate.
 */
function sail_plugin_deactivate() {
    unregister_post_type('user-register');
    flush_rewrite_rules();
} 

register_activation_hook(__FILE__, 'sail_plugin_activate');
register_deactivation_hook(__FILE__, 'sail_plugin_deactivate');