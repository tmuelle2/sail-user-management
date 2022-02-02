<?php

// No-op if not the verify email page
global $wp;
if (strpos($wp->request, 'verify-email') !== false) {
    if (!is_user_logged_in()) {
        wp_safe_redirect('https://sailhousingsolutions.org/login?redirect_to=' . urlencode(home_url(add_query_arg($_GET,$wp->request))) );
        exit;
    }
    error_log('Attempting to verify email ' . rawurldecode($_GET['email']) . ' with code ' . rawurldecode($_GET['verification_key']));
    // Ensure query string parameters exist 
    if (isset($_GET['verification_key']) && isset($_GET['email'])
        && email_exists(rawurldecode($_GET['email']))) {

        $verification_key = rawurldecode($_GET['verification_key']);
        $email = rawurldecode($_GET['email']);

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

            // Check if they are a paid member and send them a welcome email if they are
            $isPaid = false;
            if ($user->isPaidMember) {
                $isPaid = true;
            }
            else if ($user->familyId != null) {
                $pquery = "SELECT * FROM `sail_users` WHERE familyId = ";
                $pquery .= $user->familyId;
                $results = $wpdb->get_results($pquery);

                foreach($results as $fm) {
                    if ($fm->userId != $user->userId && $fm->isPaidMember) {
                        $isPaid = true;
                    }
                }
            }
            else {
                // do nothing
            }

            if ($isPaid) {
                $headers = array('Content-Type: text/html; charset=UTF-8');
                ob_start();
                include('/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/emails/welcome-email.html');
                $body = ob_get_contents();
                ob_end_clean();
                wp_mail($user->email, "Welcome to SAIL!", $body, $headers);
            }

            error_log($updated === false);
            error_log($updated === 0);
            error_log($updated >= 0);
            nocache_headers();
            wp_safe_redirect('https://sailhousingsolutions.org/success-message?title=Thank you, your email has been verified.&message=%3Ca%20href%3D%22https%3A%2F%2Fsailhousingsolutions.org%2Fuser%22%3EClick%20here%20to%20go%20to%20your%20profile%20page.%3C%2Fa%3E');
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