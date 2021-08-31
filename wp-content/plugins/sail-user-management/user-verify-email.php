<?php

// No-op if not the verify email page
if (str_contains($wp->request, 'verify-email')) {
    $verification_key = $_GET['verification_key'];
    $email = $_GET['email'];

    // Ensure query string parameters exist 
    if ( strlen($verification_key) > 0 && strlen($email) > 0 && username_exists($email) && email_exists($email) ) {
        // Get sail user from DB
        global $wpdb;
        global $wp;
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