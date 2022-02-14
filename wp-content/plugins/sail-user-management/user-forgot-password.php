<?php

$email = $_POST['email'];
$user = get_user_by( 'login', $email );
if ( username_exists($email) && email_exists($email) && !is_wp_error( $user )) {

    $reset_key = get_password_reset_key($user);
    $user_login = $user->user_login;
    $encode_RK = rawurlencode($reset_key);
    $encode_UL = rawurlencode($user_login);
    $url = esc_url_raw( "https://sailhousingsolutions.org/change-password" . "?pw_reset_key=$encode_RK&user_email=$encode_UL" );
    
    $message = "Hello ";
    $message .= $email;
    $message .= "!\r\n\r\n";
    $message .= "Someone has requested a link to reset your password, and you can do this through the link below:\r\n\r\n";
    $message .= $url;
    $message .= "\r\n\r\nIf you didn't request this, please ignore this email.";
    $message .= "\r\n\r\nYour password won't change until you access the link above and create a new one.";

    wp_mail( $user_login, "SAIL Password Reset Link", $message );
    
    if ( is_wp_error( $user ) ) {
        // Fail redirect
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org');
        exit;
    }
    else {
        // Success redirect
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org');
        exit;
    }    
}else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}
