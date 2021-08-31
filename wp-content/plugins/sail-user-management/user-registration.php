<?php

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $USER_DB_FIELDS;
global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($USER_DB_FIELDS as $element => $format) {
    if (isset($_POST[$element])) {
        $data[$element] = $_POST[$element];
    }
    else {
        $data[$element] = null;
    }
    $formats[] = $format;
}

// Create Wordpress user
$email = $_POST['email'];
$password = $_POST['password'];
if ( !username_exists($email) && !email_exists($email)) {
    // Profile Picture stuff
    // TODO: make profile pics live here:
    // $target_dir_location = '/_home2/sailhou1/public_html/wp-content/uploads/profilePictures/';
    if (isset($_FILES['profilePicture']) && isset($_FILES['profilePicture']['name']) && isset($_FILES['profilePicture']['name'])
        && !empty($_FILES['profilePicture']['name']) && !empty($_FILES['profilePicture']['name'])
    ) {
        $name_file = $_FILES['profilePicture']['name'];
        $tmp_name = $_FILES['profilePicture']['tmp_name'];
        $upload = wp_upload_bits($_FILES['profilePicture']['name'], null, file_get_contents($_FILES['profilePicture']['tmp_name']));

        if(!$upload['error']) {
            $data['profilePicture'] = $upload['url'];
        }
        else {

        }
        
    }
    
    // Send verification email
    $email_verification_key = uniqid('sail-email-verification-', true);
    $url = esc_url_raw( "https://sailhousingsolutions.org/verify-email" . "?verification_key=$email_verification_key&email=$email" );
    
    $message = "Hello ";
    $message .= $_POST['firstName'];
    $message .= "!\r\n\r\n";
    $message .= "Thanks for joining SAIL! In order to ensure that your email is configured correctly, please verify it by clicking this link:\r\n\r\n";
    $message .= $url;
    $message .= "\r\n\r\nIf you didn't sign-up for SAIL, please ignore this email.";

    wp_mail( $email, "SAIL Email Verification", $message );
    $data['emailVerificationKey'] = $email_verification_key;
    $data['emailVerified'] = false;

    // Create Wordpress user
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
