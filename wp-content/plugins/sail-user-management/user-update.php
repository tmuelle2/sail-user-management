<?php

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $USER_DB_FIELDS;
global $wpdb;

if (is_user_logged_in()) {
    
    $cur_user_array = get_sail_user_array();

    // TODO: Refactor with user-registration
    // Extract form and format data
    $data = array();
    $formats = array();
    foreach($USER_DB_FIELDS as $element => $format) {
        if (isset($_POST[$element])) {
            $data[$element] = $_POST[$element];
        }
        else {
            $data[$element] = $cur_user_array[$element];
        }
        $formats[] = $format;
    }

    $cur_user = get_sail_user();
    
    // TODO: Refactor with user-registration
    if (isset($_FILES['profilePicture']) && isset($_FILES['profilePicture']['name']) && isset($_FILES['profilePicture']['name'])
        && !empty($_FILES['profilePicture']['name']) && !empty($_FILES['profilePicture']['name']) 
        ) {
        $name_file = $_FILES['profilePicture']['name'];
        $tmp_name = $_FILES['profilePicture']['tmp_name'];
        $upload = wp_upload_bits($_FILES['profilePicture']['name'], null, file_get_contents($_FILES['profilePicture']['tmp_name']));

        if(!$upload['error']) {
            $data['profilePicture'] = $upload['url'];
        }
        else {

        }
    } else {
        $data['profilePicture'] = $cur_user->profilePicture;
    }

    // Update SAIL users db table
    $wpdb->update('sail_users', $data, array('userId' => $cur_user->userId), $formats);

    // Success redirect
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/user');
    exit;
}

// Fail redirect 
nocache_headers();
wp_safe_redirect('https://sailhousingsolutions.org/error');
exit;
