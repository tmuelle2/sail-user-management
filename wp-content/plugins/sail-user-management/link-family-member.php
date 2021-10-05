<?php

// No-op if not the link account page
global $wp;
if (strpos($wp->request, 'link-family-member') !== false) {
    error_log('Attempting to link family member with ' . $_GET['email'] . ' with code ' . $_GET['family_linking_key']);
    // Ensure query string parameters exist 
    if (isset($_GET['family_linking_key']) && isset($_GET['email']) 
        && email_exists($_GET['email']) && is_user_logged_in()) {

        $family_linking_key = $_GET['family_linking_key'];
        $email = $_GET['email'];

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
        }
    }
}
