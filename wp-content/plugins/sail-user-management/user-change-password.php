<?php

function get_request_parameter( $key, $default = '' ) {
    // If not request set
    if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
        return $default;
    }

    // Set so process it
    return strip_tags( (string) wp_unslash( $_REQUEST[ $key ] ) );
}

$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

if ($password != $confirmPassword) {
    // url is missing the key for password reset
    error_log("[user-change-password.php] ERROR: password and confirmPassword do not match!");
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}

if (is_user_logged_in()) {
    $user = wp_get_current_user();
    wp_set_password($password, $user->ID);
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/login/');
    exit;
}
else {
    $key = get_query_var('pw_reset_key', '');
    $email = get_query_var('user_email', '');

    $key2 = get_request_parameter('pw_reset_key', '');
    $email2 = get_request_parameter('user_email', '');

    if (strlen($key) != 0 || strlen($email) != 0) {

        $user = check_password_reset_key($key, $email);

        if ( username_exists($email) && email_exists($email) && !is_wp_error( $user )) {
            wp_set_password($password, $user->ID);
            nocache_headers();
            wp_safe_redirect('https://sailhousingsolutions.org/login/');
            exit;
        }
        else {
            error_log("[user-change-password.php] ERROR: check_password_reset_key() failed or user does not exist!");
            nocache_headers();
            wp_safe_redirect('https://sailhousingsolutions.org/error');
            exit;
        }

    }
    else {
        // url is missing the key for password reset
        error_log("[user-change-password.php] ERROR: User is not logged in and trying to reset their password without a key/email parameter in the url.");
        error_log("Now Pringting debug vars: ");
        print_r($key, true);
        print_r($email, true);
        print_r($key2, true);
        print_r($email2, true);
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/error');
        exit;
    }
}