<?php

namespace Sail\Data\Model;

use Sail\Data\Dao\FamilyDao;
use Sail\Data\Model\Family;
use Sail\Utils\Logger;
use WP_REST_Request;

class User extends SailDataObject
{
  use Logger;

  // Mapping of fields to database format
  private const USER_DB_FIELDS = array(
    'userId' => '%s',
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

  private Family $family;

  public function __construct(array $data)
  {
    parent::__construct($data, self::USER_DB_FIELDS);
  }

  public static function fieldKeys(): array
  {
    return self::USER_DB_FIELDS;
  }

  public function isDuePayingUser(): bool
  {
    return !$this->isPastDue();
  }

  // Helper function to get the most recent payment date of the user and their family members
  public function getMostRecentPaymentDate() {

    if (!isset($this->family)) {
      $this->family = FamilyDao::getInstance()->getFamilyMembersForUser($this);
    }
    $datesToCheck = array();
    if ($this->lastDuePaymentDate != null) {
      array_push($datesToCheck, $this->lastDuePaymentDate);
    }
    if ($this->family != null) {
      foreach ($this->family->getMembers() as $fm) {
        if ($fm->userId != $this->userId && $fm->lastDuePaymentDate != null) {
          array_push($datesToCheck, $fm->lastDuePaymentDate);
        }
      }
    }

    if (count($datesToCheck) == 0)
      return "1984-1-1"; // this account/family has never paid, just return something that will be expired

    // Take the array of string dates, map them to unix time stamps, find the most recent date and convert it back to an easy format to work with
    return date("Y-m-d", strval(max(array_map('strtotime', $datesToCheck))));
  }

  public function willBePastDueSoon(): bool
  {
      $dateToCheck = $this->getMostRecentPaymentDate();

      if (strtotime($dateToCheck) < strtotime('-11 months')) {
          return true; // paid over 11 months ago so will be due soon
      } else {
          return false; // paid within the last 11 months so good for a bit
      }
  }

  public function isPastDue(): bool
  {
    $dateToCheck = $this->getMostRecentPaymentDate();

      if (strtotime($dateToCheck) < strtotime('-1 year')) {
          return true;
      } else {
          return false;
      }
  }

  public function calculateExpirationYear(): string
  {
    $dateToCheck = $this->getMostRecentPaymentDate();
    return date('Y-m-d', strtotime('+1 year', strtotime($dateToCheck)));
  }

  public function isSecondaryLinkedAccount(): bool
  {
    $dateToCheck = $this->getMostRecentPaymentDate();
    if ($dateToCheck != $this->lastDuePaymentDate)
      return true;
    else
      return false;
  }
}
