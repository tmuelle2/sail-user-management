<?php

use Sail\Utils\WebUtils;

$HOME_DIR = '/_home2/sailhou1/public_html/';
global $FC_DB_FIELDS;
global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($FC_DB_FIELDS as $element => $format) {
    if (isset($_POST[$element])) {
        $data[$element] = $_POST[$element];
    }
    else {
        $data[$element] = null;
    }
    $formats[] = $format;
}

// TODO: add paywall as double check
if (is_user_logged_in()) {
    // Profile Picture stuff
    // TODO: make profile pics live here:
    // $target_dir_location = '/_home2/sailhou1/public_html/wp-content/uploads/profilePictures/';

    $user = wp_get_current_user();
    $query = "SELECT * FROM `fc_members` WHERE userId = ";
    $query .= $user->ID;

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
            $data['profilePicture'] = "http://sailhousingsolutions.org/wp-admin/identicon.php?size=200&hash=" . md5($user->user_login);
        }
    } else {
        $data['profilePicture'] = "http://sailhousingsolutions.org/wp-admin/identicon.php?size=200&hash=" . md5($user->user_login);
    }

    // check if fc profile already exists for this user
    $result = $wpdb->get_results($query);

    // Create a fc member
    if (count($result) == 0) {
        $data['userId'] = $user->ID;
        $data['namePreference'] = 'Nickname';
        // Insert the database row
        $wpdb->insert('fc_members', $data, $formats);

        wp_mail("info@sailhousingsolutions.org", "New Friendship Connect Profile Created", "If you are a Wordpress Admin, please review the SAIL reference of the new FC Profile by going to the DATABASE ACCESS panel on the admin page.");

        // Success redirect
        WebUtils::redirect('/user');
        exit;
    }
    else {
<<<<<<< HEAD
        // Fail redirect
        nocache_headers();
        wp_safe_redirect('https://sailhousingsolutions.org/error');
        exit;
    }
} else {
    // Fail redirect
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org/error');
=======
        // Fail redirect 
        WebUtils::redirect('/error');
        exit;
    }
} else {
    // Fail redirect 
    WebUtils::redirect('/error');
>>>>>>> refactor
    exit;
}
