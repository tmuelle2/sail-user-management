<?php

// No-op if not the verify email page
global $wp;
if (strpos($wp->request, 'verify-email') !== false) {
    // Ensure query string parameters exist 
    if ( strlen($verification_key) > 0 && strlen($email) > 0 
        && isset($_GET['verification_key']) && isset($_GET['email']) 
        && username_exists($_GET['email']) && email_exists($_GET['verification_key']) ) {

        $verification_key = $_GET['verification_key'];
        $email = $_GET['email'];

        // Get sail user from DB
        global $wpdb;
        $query = "SELECT * FROM `sail_users` WHERE email = " . $email;
        $user = $wpdb->get_row($query);
        if (isset($user) && $user->emailVerificationKey == $verification_key) {
            // TODO: Refactor with user-registration
            // Get format data
            $formats = array();
            foreach($USER_DB_FIELDS as $element => $format) {
                $formats[] = $format;
            }

            $user->emailVerified = true;
            $wpdb->update('sail_users', $user, array('userId' => $user->userId), $formats);
            exit;
        }
    }

    // Redirect to error page
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}