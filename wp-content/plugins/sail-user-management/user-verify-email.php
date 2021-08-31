<?php

// No-op if not the verify email page
global $wp;
if (strpos($wp->request, 'verify-email') !== false) {
    error_log('Attempting to verify email' . $_GET['email'] . ' with code ' . $_GET['verification_key']);
    // Ensure query string parameters exist 
    if (isset($_GET['verification_key']) && isset($_GET['email']) 
        && email_exists($_GET['email'])) {

        $verification_key = $_GET['verification_key'];
        $email = $_GET['email'];

        // Get sail user from DB
        global $wpdb;
        $query = "SELECT * FROM `sail_users` WHERE email = '" . $email . "'";
        $user = $wpdb->get_row($query);
        error_log('Got user');
        error_log(print_r($user, true));
        if (isset($user) && $user->emailVerificationKey == $verification_key) {
            // TODO: Refactor with user-registration
            // Get format data
            global $USER_DB_FIELDS;
            $formats = array();
            foreach($USER_DB_FIELDS as $element => $format) {
                $formats[] = $format;
            }

            error_log('Updating user');
            $user->emailVerified = true;
            error_log(print_r($user, true));
            $updated = $wpdb->update('sail_users', $user, array('userId' => $user->userId), $formats);
            error_log($updated);
        } else {
            // Redirect to error page
            wp_safe_redirect('https://sailhousingsolutions.org/error');
            exit;
        }
    } else {
        // Redirect to error page
        wp_safe_redirect('https://sailhousingsolutions.org/error');
        exit;
    }
}