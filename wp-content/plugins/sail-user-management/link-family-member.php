<?php

// No-op if not the link account page
global $wp;
if (strpos($wp->request, 'link-family-member') !== false) {
    if (!is_user_logged_in()) {
        wp_safe_redirect('https://sailhousingsolutions.org/login?redirect_to=' . urlencode(home_url(add_query_arg($_GET,$wp->request))) );
        exit;
    }
    error_log('Attempting to link family member with ' . $_GET['email'] . ' with code ' . $_GET['family_linking_key']);
    // Ensure query string parameters exist 
    if (isset($_GET['family_linking_key']) && isset($_GET['email']) 
        && email_exists(rawurldecode($_GET['email'])) && is_user_logged_in()) {

        $family_linking_key = rawurldecode($_GET['family_linking_key']);
        $email = rawurldecode($_GET['email']);

        // Get sail user from DB
        global $wpdb;
        $query = "SELECT * FROM `sail_users` WHERE email = '" . $email . "'";
        $link_user = $wpdb->get_row($query, 'ARRAY_A');
        error_log('Got user intiating link:');
        error_log(print_r($link_user, true));
        if (isset($link_user) && $link_user['familyLinkingKey'] == $family_linking_key ) {
            $cur_user_array = get_sail_user_array();
            error_log('Got user performing link:');
            error_log(print_r($cur_user_array, true));

            if ($cur_user_array['userId'] == $link_user['userId']) {
                error_log('Account Linking ERROR: this userId tried to link an account to itself: ');
                error_log(print_r($cur_user_array['userId'], true));
                wp_safe_redirect('https://sailhousingsolutions.org/error');
                exit;
            }

            $formats = array();
            global $USER_DB_FIELDS;
            foreach($USER_DB_FIELDS as $element => $format) {
                $formats[] = $format;
            }

            $family_id = null;
            if (isset($cur_user_array['familyId'])) {
                $family_id = $cur_user_array['familyId'];
            } else if (isset($link_user['familyId'])) {
                $family_id = $link_user['familyId'];
            } else {
                $family_id = $cur_user_array['userId'];
            }
            $cur_user_array['familyId'] = $family_id;
            $link_user['familyId'] = $family_id;
            $wpdb->update('sail_users', $cur_user_array, array('userId' => $cur_user_array['userId']), $formats);
            $wpdb->update('sail_users', $link_user, array('userId' => $link_user['userId']), $formats);
            $relation = array('familyId' => $family_id, 'userId1' => $cur_user_array['userId'], 'userId2' => $link_user['userId']);
            $wpdb->insert('sail_family', $relation, array('%d', '%d', '%d'));


            // Do some checks and send welcome email
            // NOTE: These checks are not airtight, some users might get the welcome email twice or not at all.
            //       Once an account has reached the state where it has a verified email and is either paid or linked to a paid account the welcome email should be sent.
            // TODO: We should add a bool to the sail-users table called sentWelcomeEmail so we don't send the email twice but...
            // we should do that when we actually know the email was sent for sure, wp_mail does not do that :/
            if ($link_user['isPaidMember'] && !$cur_user_array['isPaidMember'] && $cur_user_array['emailVerified']) {
                $headers = array('Content-Type: text/html; charset=UTF-8');
                ob_start();
                include('/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/emails/welcome-email.html');
                $body = ob_get_contents();
                ob_end_clean();
                wp_mail($cur_user_array['email'], "Welcome to SAIL!", $body, $headers);
            }

            nocache_headers();
            wp_safe_redirect('https://sailhousingsolutions.org/success-message?title=Accounts successfully linked.&message=%3Ca%20href%3D%22https%3A%2F%2Fsailhousingsolutions.org%2Fuser%22%3EClick%20here%20to%20go%20to%20your%20profile%20page.%3C%2Fa%3E');
            exit;
        }
    }
}
