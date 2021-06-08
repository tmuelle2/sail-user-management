<?php

// Login Wordpress user
$email = $_POST['email'];
$password = $_POST['password'];
$remember = $_POST['remember'];
if ( username_exists($email) && email_exists($email)) {
    $creds = array(
        'user_login'    => $email,
        'user_password' => $password,
        'remember'      => $remember
    );
 
    $user = wp_signon( $creds, is_ssl() );
    
    if ( is_wp_error( $user ) ) {
        // Fail redirect
        echo $user->get_error_message();
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/?page_id=422');
        exit;
    }
    else {
        // Success redirect
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/?page_id=249');
        exit;
    }    
}else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/?page_id=422');
    exit;
}
