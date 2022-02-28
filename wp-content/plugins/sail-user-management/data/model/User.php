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
    if ($this->isPaidMember) {
      return true;
    } else if (!isset($this->family)) {
      $this->family = FamilyDao::getInstance()->getFamilyMembersForUser($this);
    }
    if ($this->family != null) {
      foreach ($this->family->getMembers() as $fm) {
        if ($fm->userid != $this->userid && $fm->ispaidmember) {
          return true;
        }
      }

      return false;
    } else {
      return false;
    }
  }
}
