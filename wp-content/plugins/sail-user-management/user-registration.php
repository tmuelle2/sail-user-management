<?php

global $USER_DB_FIELDS;
global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($USER_DB_FIELDS as $element => $format) {
    $data[$element] = $_POST[$element];
    $formats[] = $format;
}

// Create Wordpress user
$email = $_POST['email'];
$password = $_POST['password'];
if ( !username_exists($email) && !email_exists($email)) {
    $user_id = wp_create_user(
        $email,
        $password,
        $email
    );
    $data['userId'] = $user_id;

    // Insert into SAIL users db table
    $wpdb->insert('sail_users', $data, $formats);

    // Signon user
    $creds = array('user_login' => $email, 'user_password' => $password);
    $user = wp_signon( $creds, is_ssl() );

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
