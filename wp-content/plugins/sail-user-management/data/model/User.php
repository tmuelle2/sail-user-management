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
        if ($fm->userid != $this->userid && $fm->lastDuePaymentDate != null) {
          array_push($datesToCheck, $fm->lastDuePaymentDate);
        }
      }
    }

    if (count($datesToCheck) == 0)
      return "1984-1-1"; // this account/family has never paid, just return something that will be expired

    // Take the array of string dates, map them to unix time stamps, find the most recent date and convert it back to an easy format to work with
    return date("Y-m-d", strval(max(array_map('strtotime', $datesToCheck))));
  }

  // Based off the following statements:
  // LAST PAYMENT DATE BEFORE SEPT 1st 2022 = "PAST DUE SOON" starts OCT 1st 2022
  // LAST PAYMENT DATE ON/AFTER SEPT 1st 2022 = "PAST DUE SOON" starts OCT 1st 2023
  public function willBePastDueSoon(): bool
  {
      $dateToCheck = $this->getMostRecentPaymentDate();

      $lpDate = explode("-", $dateToCheck);
      $curDate = explode("-", date("Y-m-d"));

      if ($curDate[0] > $lpDate[0] && $curDate[0] - $lpDate[0] == 1) // Payment was from last year
      {
        if ($lpDate[1] > 8) // They paid last year in/after Sept
        {
          if ($curDate[1] > 9) // It's currently Oct or later, need to warn them!
          {
            return true;
          }
          else // It's not Oct yet, so they are good for a bit
          {
            return false;
          }
        }
        else // Paid last year before Sept so they are probs already expired but might have a little time left so warn them
        {
          return true;
        }
      }
      else if ($curDate[0] == $lpDate[0]) // They paid sometime this year
      {
        if ($lpDate[1] > 8) // They paid this year in/after Sept so they are good for awhile
        {
          return false;
        }
        else // Paid this year before Sept
        {
          if ($curDate[1] > 9) // It's currently Oct or later, need to warn them!
          {
            return true;
          }
          else // It's not Oct yet, so they are good for a bit
          {
            return false;
          }
        }
      }
      else // They paid two years ago so warn them (this won't matter if they are expired)
      {
        return true;
      }


  }

  // Based off the following statements:
  // LAST PAYMENT DATE BEFORE SEPT 1st 2022 = GOOD UNTIL JAN 31st 2023
  // LAST PAYMENT DATE ON/AFTER SEPT 1st 2022 = GOOD UNTIL JAN 31st 2024
  public function isPastDue(): bool
  {
    $dateToCheck = $this->getMostRecentPaymentDate();

    $lpDate = explode("-", $dateToCheck);
    $curDate = explode("-", date("Y-m-d"));

    if ($curDate[0] > $lpDate[0]) // Payment was from last year or earlier
    {
      if ($curDate[0] - $lpDate[0] > 2) // Paid 3+ years ago, dues are expired!
      {
        return true;
      }
      else // Paid either last year or 2 years ago
      {
        if ($curDate[0] - $lpDate[0] == 2) // Payment was from 2 years ago
        {
          if ($curDate[1] > 1) // Paid 2 years ago and we are already past Jan this year, dues are expired!
          {
            return true;
          }
          else if ($lpDate[1] > 8) // We are still in Jan this year and they paid 2 years ago in/after Sept so they still have a few weeks before being expired
          {
            return false;
          }
          else // They paid 2 years ago and before Sept 1st, dues are expired!
          {
            return true;
          }
        }
        else // Payment was from last year
        {
          if ($lpDate[1] > 8) // They paid last year in/after Sept so they are good for the whole year
          {
            return false;
          }
          else if ($curDate[1] == 1) // They paid last year but it's still Jan so they are good
          {
            return false;
          }
          else // They paid last year before Sept 1st and it's past Jan, dues are expired! (which is dumb but whatever)
          {
            return true;
          }
        }
      }
    }
    else // Paid sometime this year, they have to be good
    {
      return false;
    }

  }

  public function calculateExpirationYear(): string
  {
    $dateToCheck = $this->getMostRecentPaymentDate();

    $lpDate = explode("-", $this->dateToCheck);
    $curDate = explode("-", date("Y-m-d"));

    if ($lpDate[1] > 8) // Paid in/after Sept, expires at the "end" of the next year (technically a month after that but whatever)
    {
      return strval(intval($lpDate[0], 10) + 1);
    }
    else // Paid before Sept, expires at the "end" of the same year they paid
    {
      return $lpDate[0];
    }
  }
}
