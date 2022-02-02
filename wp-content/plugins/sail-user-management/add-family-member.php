<?php

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $USER_DB_FIELDS;
global $wpdb;

if (is_user_logged_in()) {
    $email_input = $_POST['email'];
    $cur_user = get_sail_user();
    $cur_user_array = get_sail_user_array();

    $data = array();
    $formats = array();
    foreach($USER_DB_FIELDS as $element => $format) {
        $data[$element] = $cur_user_array[$element];
        $formats[] = $format;
    }

    // Send verification email
    $family_linking_key = uniqid('family-linking-key-', true);
    $url = rawurlencode( "https://sailhousingsolutions.org/link-family-member" . "?family_linking_key=$family_linking_key&email=$cur_user->email" );
    
    $message = "Hello!\r\n\r\n";
    $message .= "A SAIL user with the email ";
    $message .= $cur_user->email;
    $message .= " is requesting that your accounts are linked together as part of a family to share the benefits of a SAIL membership. ";
    $message .= "To finish the linking process use the link below. Before clicking the link, please make sure you are logged in using the account associated with this email: "; 
    $message .= $email_input;
    $message .= "\r\n\r\n";
    $message .= $url;
    $message .= "\r\n\r\nIf you do not have an account with SAIL, please ignore this email.";

    wp_mail( $email_input, "SAIL Family Account Link Request", $message );
    $data['familyLinkingKey'] = $family_linking_key;

    error_log("[Sending link-family-member email]");
    print_r($data, true);
    // Update SAIL users db table
    $wpdb->update('sail_users', $data, array('userId' => $cur_user->userId), $formats);

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
