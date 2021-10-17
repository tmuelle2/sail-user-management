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
        wp_safe_redirect('https://sailhousingsolutions.org/error');
        exit;
    }
    else {
        // Success redirect
        wp_set_current_user( $user->ID, $user->data->user_login );

        if (isset($_POST['redirect_to']) && strpos($_POST['redirect_to'], 'https://sailhousingsolutions.org') !== false) {
            wp_safe_redirect($_POS['redirect_to']);
            exit;
        }
        else {
            wp_safe_redirect('https://sailhousingsolutions.org/user');
            exit;
        }
    }    
}else {
    // Fail redirect 
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}
