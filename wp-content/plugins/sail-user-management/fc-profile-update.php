<?php

use Sail\Utils\WebUtils;

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $FC_DB_FIELDS;
global $wpdb;

if (is_user_logged_in()) {
    
    $cur_member_array = get_fc_member_array();

    // TODO: Refactor with user-registration
    // Extract form and format data
    $data = array();
    $formats = array();
    foreach($FC_DB_FIELDS as $element => $format) {
        if (isset($_POST[$element])) {
            $data[$element] = $_POST[$element];
        }
        else {
            $data[$element] = $cur_member_array[$element];
        }
        $formats[] = $format;
    }

    $cur_user = get_sail_user();
    $cur_member = get_fc_member();
    
    //error_log("[fc-profile-update] $_FILES var dump: ");
    //error_log(print_r($_FILES['profilePicture']['name'], true));

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
            error_log("[fc-profile-update] profilePicture upload failed: ");
            error_log(print_r($upload['error'], true));
        }
    } else {
        $data['profilePicture'] = $cur_member->profilePicture;
    }

    // Update FC members db table
    $wpdb->update('fc_members', $data, array('userId' => $cur_member->userId), $formats);

    // Success redirect
    WebUtils::redirect('/user');
    exit;
}

// Fail redirect 
WebUtils::redirect('/error');
exit;
