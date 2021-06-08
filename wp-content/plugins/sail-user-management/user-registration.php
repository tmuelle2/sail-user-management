<?php

// Mapping of form element to database format
$formElements = array(
    'firstName' => '%s',
    'lastName' => '%s', 
    'email' => '%s', 
    'addrLine1' => '%s', 
    'addrLine2' => '%s', 
    'city' => '%s', 
    'state' => '%s', 
    'zipCode' => '%d', 
    'phoneNumber' => '%s',
    'profilePicture' => '%s', 
    'gender' => '%s', 
    'dob' => '%s', 
    'contactViaEmail' => '%s', 
    'contactViaText' => '%s', 
    'role' => '%s', 
    'situation' => '%s', 
    'reference' => '%s',
    'timeframe' => '%s',
    'portInterest' => '%s',
    'portInterestParticular' => '%s',
    'backgroundCheck' => '%s',
    'newsletter' => '%s',
    'additionalInfo' => '%s'
);

global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($formElements as $element => $format) {
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

    // Success redirect
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/?page_id=249');
    exit;
} else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/?page_id=422');
    exit;
}
