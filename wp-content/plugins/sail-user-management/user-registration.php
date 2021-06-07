<?php

$formElements = array(
    'firstName' => '%s',
    'lastName' => '%s', 
    'email' => '%s', 
    'password' => '%s', 
    'addrLine1' => '%s', 
    'addrLine2' => '%s', 
    'city' => '%s', 
    'state' => '%s', 
    'zipCode' => '%d', 
    'phoneNumber' => '%s', 
    'gender' => '%s', 
    'dob' => '%s', 
    'contactViaEmail' => '%s', 
    'contactViaText' => '%s', 
    'role' => '%s', 
    'situation' => '%s', 
    'reference' => '%s',
    'timeframe' => '%s',
    'portInterest' => '%s',
    'portInterestParticular' => '%s',
    'backgroundCheck' => '%s',
    'newsletter' => '%s',
    'additionalInfo' => '%s'
);

global $wpdb;

$data = array()
$formats = array()
foreach($formElements as $element => $format) {
    $data[$element] = $_POST[$element];
    $formats[] = $format;
}

$wpdb->insert('sail_users', $data, $formats);

header("Location: https://media.giphy.com/media/Q81NcsY6YxK7jxnr4v/giphy.gif");