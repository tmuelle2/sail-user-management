<?php

$email = $_POST['email']
$user = get_user_by( 'login', $email );
if ( username_exists($email) && email_exists($email) && !is_wp_error( $user )) {

    $reset_key = get_password_reset_key($user);
    $user_login = $user->user_login;
    $url = esc_url_raw( "https://sailhousingsolutions.org/change-password" . "?action=change_password&key=$reset_key&login=$user_login" );
    $message = $url;

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
