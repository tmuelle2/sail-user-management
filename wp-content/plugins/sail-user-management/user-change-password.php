<?php

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
    $key = get_query_var('key', '');
    $email = get_query_var('login', '');

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
        print_r($_GET['login'], true);
        print_r($_GET['key'], true);
        print_r($_GET, true);
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/error');
        exit;
    }
}