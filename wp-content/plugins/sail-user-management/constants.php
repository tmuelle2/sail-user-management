<?php

$PAGES_DIR = $HOME_DIR . 'pages/';

// Mapping of form element to database format
$USER_DB_FIELDS = array(
    'firstName' => '%s',
    'lastName' => '%s', 
    'email' => '%s', 
    'addrLine1' => '%s', 
    'addrLine2' => '%s', 
    'city' => '%s', 
    'state' => '%s', 
    'zipCode' => '%d', 
    'phoneNumber' => '%s',
    'profilePicture' => '%s', 
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