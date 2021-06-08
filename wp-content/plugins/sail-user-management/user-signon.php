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
        header('Location: https://media.giphy.com/media/d2W7eZX5z62ziqdi/giphy.gif');
    }
    else {
        // Success redirect
        wp_set_current_user( $user->ID, $user->data->user_login );
        header('Location: https://media.giphy.com/media/Q81NcsY6YxK7jxnr4v/giphy.gif');
    }    
}else {
    // Fail redirect
    header('Location: https://media.giphy.com/media/d2W7eZX5z62ziqdi/giphy.gif');
}
