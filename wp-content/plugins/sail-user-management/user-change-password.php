<?php

use Sail\Utils\WebUtils;

$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$key = $_POST['pw_reset_key'];
$email = $_POST['user_email'];

if ($password != $confirmPassword) {
    // url is missing the key for password reset
    error_log("[user-change-password.php] ERROR: password and confirmPassword do not match!");
    WebUtils::redirect('/error');
    exit;
}

if (is_user_logged_in()) {
    $user = wp_get_current_user();
    wp_set_password($password, $user->ID);
    WebUtils::redirect('/login/');
    exit;
}
else {

    if (strlen($key) != 0 || strlen($email) != 0) {

        $user = check_password_reset_key($key, $email);

        if ( username_exists($email) && email_exists($email) && !is_wp_error( $user )) {
            wp_set_password($password, $user->ID);
            WebUtils::redirect('/login/');
            exit;
        }
        else {
            error_log("[user-change-password.php] ERROR: check_password_reset_key() failed or user does not exist!");
            WebUtils::redirect('/error');
            exit;
        }

    }
    else {
        // url is missing the key for password reset
        error_log("[user-change-password.php] ERROR: User is not logged in and trying to reset their password without a key/email parameter in the url.");
        error_log("Now printing debug vars: ");
        print_r($key, true);
        print_r($email, true);
        WebUtils::redirect('/error');
        exit;
    }
}