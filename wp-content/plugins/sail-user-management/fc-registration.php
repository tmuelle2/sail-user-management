<?php

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $FC_DB_FIELDS;
global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($FC_DB_FIELDS as $element => $format) {
    $data[$element] = $_POST[$element];
    $formats[] = $format;
}

if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $query = "SELECT * FROM `fc_members` WHERE userId = ";
    $query .= $user->ID;

    // check if fc profile already exists for this user
    $result = $wpdb->get_results($query);

    // Create a port member
    if (count($result) == 0) {
        $data['userId'] = $user->ID;

        // Insert the database row
        $wpdb->insert('fc_members', $data, $formats);

        // Success redirect
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/user');
        exit;
    }
    else {
        // Fail redirect 
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/error');
        exit;
    }
} else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}
