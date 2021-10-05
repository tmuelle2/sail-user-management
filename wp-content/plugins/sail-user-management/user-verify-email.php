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
            $wpdb->show_errors = true;
            $updated = $wpdb->update('sail_users', (array) $user, array('userId' => $user->userId), $formats);
            error_log($updated === false);
            error_log($updated === 0);
            error_log($updated >= 0);
            wp_redirect('https://sailhousingsolutions.org/success-message?title=Thank you, your email has been verified.&message=%3Ca%20href%3D%22https%3A%2F%2Fsailhousingsolutions.org%2Fuser%22%3EClick%20here%20to%20go%20to%20your%20profile%20page.%3C%2Fa%3E');
            exit;
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