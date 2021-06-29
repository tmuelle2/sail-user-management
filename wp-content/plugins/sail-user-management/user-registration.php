<?php

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $USER_DB_FIELDS;
global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($USER_DB_FIELDS as $element => $format) {
    $data[$element] = $_POST[$element];
    $formats[] = $format;
}

// Create Wordpress user
$email = $_POST['email'];
$password = $_POST['password'];
if ( !username_exists($email) && !email_exists($email)) {
    $user_id = wp_create_user(
        $email,
        $password,
        $email
    );
    $data['userId'] = $user_id;

    // Profile Picture stuff
    // ensure nonce is valid
    if(isset( $_POST['profilePicture_nonce']) && wp_verify_nonce( $_POST['profilePicture_nonce'], 'profilePicture' )) {

        // These files need to be included as dependencies when on the front end.
        require_once( $HOME_DIR . 'wp-admin/includes/image.php' );
        require_once( $HOME_DIR . 'wp-admin/includes/file.php' );
        require_once( $HOME_DIR . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload( 'profilePicture', 0);

        if ( is_wp_error( $attachment_id ) ) {
            // There was an error uploading the image.
        } else {
            // The image was uploaded successfully!
            $data['profilePictureId'] = $attachment_id;
        }
    }

    // Insert into SAIL users db table
    $wpdb->insert('sail_users', $data, $formats);

    // Signon user
    $creds = array('user_login' => $email, 'user_password' => $password);
    $user = wp_signon( $creds, is_ssl() );

    // Success redirect
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/user');
    exit;
} else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
    exit;
}
