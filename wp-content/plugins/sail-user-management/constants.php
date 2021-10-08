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
    'dob' => '%s', 
    'contactViaEmail' => '%s', 
    'contactViaText' => '%s', 
    'role' => '%s', 
    'situation' => '%s',
    'reference' => '%s',
    'timeframe' => '%s',
    'newsletter' => '%s',
    'additionalInfo' => '%s',
    'readTermsOfService' => '%s',
    'emailVerified' => '%s',
    'emailVerificationKey' => '%s',
    'isPaidMember' => '%s',
    'lastDuePaymentDate' => '%s',
    'familyLinkingKey' => '%s',
    'familyId' => '%s'
);

// Mapping of form element to database format
$FC_DB_FIELDS = array(
    'authorized' => '%s',
    'profilePicture' => '%s',
    'gender' => '%s',
    'namePreference' => '%s',
    'nickname' => '%s', 
    'primaryContactType' => '%s',
    'activities' => '%s', 
    'hobbies' => '%s', 
    'typicalDay' => '%s', 
    'strengths' => '%s', 
    'makesYouHappy' => '%s',
    'lifesVision' => '%s', 
    'supportRequirements' => '%s',
    'referenceName' => '%s',
    'referencePhoneNumber' => '%s',
    'referenceEmail' => '%s',
    'primaryContact' => '%s'
);

// Mapping of form element to database format for ports
$PORT_DB_FIELDS = array(
    'currentSituation' => '%s',
    'idealSituation' => '%s',
    'typicalDay' => '%s',
    'makesChildHappy' => '%s'
);
