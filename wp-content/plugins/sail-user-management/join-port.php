<?php

use Sail\Utils\WebUtils;
// idk why but this was causing an error but the row was being added to the db
//$HOME_DIR = '/_home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
//include($HOME_DIR . 'constants.php');
//global $PORT_DB_FIELDS;
$PORT_DB_FIELDS = array(
    'currentSituation' => '%s',
    'idealSituation' => '%s',
    'typicalDay' => '%s',
    'makesChildHappy' => '%s'
);


global $wpdb;

// Extract form and format data
$data = array();
$formats = array();
foreach($PORT_DB_FIELDS as $element => $format) {
    $data[$element] = $_POST[$element];
    $formats[] = $format;
}

// Create a port member
if (is_user_logged_in()) {
    $data['userId'] = get_current_user_id();

    // Insert into port members db table
    $wpdb->insert('port_members', $data, $formats);

    // Success redirect
    WebUtils::redirect('/user');
    exit;
} else {
    // Fail redirect 
    WebUtils::redirect('/error');
    exit;
}
