<?php

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $USER_DB_FIELDS;
global $wpdb;

if (is_user_logged_in()) {
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error-message?title=Register Error&message=You are attempting to create an account while logged in. Please sign out of this account to create a new one.');
    exit;
}

// Extract form and format data
$data = array();
$formats = array();
foreach($USER_DB_FIELDS as $element => $format) {
    if (isset($_POST[$element])) {

        // Special check if the data is an array for multi-select checkbox inputs
        if (is_array($_POST[$element])) {
            if (!empty($_POST[$element])) {
                $combined = "";
                foreach($_POST[$element] as $check) {
                    $combined .= $check;
                    $combined .= "|"; // using piped seperated string since commas are used
                }
                $data[$element] = substr($combined, 0, -1);
            }
        }
        else {
            $data[$element] = $_POST[$element];
        }
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
    
    // Send verification email
    $email_verification_key = send_verification_email($data);

    $data['emailVerificationKey'] = $email_verification_key;
    $data['emailVerified'] = false;

    // Create Wordpress user
    error_log("[user-registration.php]: Creating wp user...");
    $user_id = wp_create_user(
        $email,
        $password,
        $email
    );
    error_log("[user-registration.php]: Wp user created: ");
    error_log(print_r($user_id, true));
    $data['userId'] = $user_id;

    // Insert into SAIL users db table
    error_log("[user-registration.php]: Attempting to create sail_user with this data: ");
    error_log(print_r($data, true));
    $wpdb->insert('sail_users', $data, $formats);
    error_log("[user-registration.php]: Sail_user created");

    // Signon user
    $creds = array('user_login' => $email, 'user_password' => $password);
    $user = wp_signon( $creds, is_ssl() );

    // Success redirect
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/upgrade-registration');
    exit;
} else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}
