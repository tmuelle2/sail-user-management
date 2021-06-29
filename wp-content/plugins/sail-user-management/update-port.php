<?php

$HOME_DIR = '/_home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
global $PORT_DB_FIELDS;

global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($PORT_DB_FIELDS as $element => $format) {
    $data[$element] = $_POST[$element];
    $formats[] = $format;
}

// Create a port member
if (is_user_logged_in()) {
    $data['userId'] = get_current_user_id();

    // TODO: Update the database row
    

    // Success redirect
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/user');
    exit;
} else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}
